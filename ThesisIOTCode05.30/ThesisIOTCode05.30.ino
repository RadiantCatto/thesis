#include <ESP8266WiFi.h>
#include <SPI.h>
#include <MFRC522.h>
#include <WiFiClient.h>

#include <ESP8266HTTPClient.h>

#define SS_PIN D2
#define RST_PIN D3

// Declaration of variables and other initializations
MFRC522 mfrc522(SS_PIN, RST_PIN);   // Create MFRC522 instance
String userName = "User";          // Set user name
bool isSignedIn = false;            // Track sign-in status
void(* resetFunc) (void) = 0;     // Declare reset function at address 0

const char* ssid = "rey";
const char* password = "a12345poco";
const char* serverAddress = "http://192.168.51.204/esptest/sensorvalue.php";  // Replace with your server address

int user_points = 0; // Initialization of accumulated user points

void setup() {
  Serial.begin(9600);
  WiFi.begin(ssid, password);
  Serial.println();
  Serial.print("Connecting to ");
  Serial.println(ssid);
  while (WiFi.status() != WL_CONNECTED) {
    delay(1000);
    Serial.print("Connecting to WiFi....");
  }
  Serial.println("");
  Serial.println("WiFi connected");
  Serial.print("IP address: ");
  Serial.println(WiFi.localIP());
  while (!Serial);      // Wait for the serial port to open

  SPI.begin();          // Initialize SPI bus
  mfrc522.PCD_Init();   // Initialize MFRC522 RFID reader

  Serial.println("Ready to read RFID cards!");
  Serial.println();
}

void loop() {
  // Look for new cards
  if (mfrc522.PICC_IsNewCardPresent() && mfrc522.PICC_ReadCardSerial()) {
    Serial.println("Card detected:");
    String cardID = "";
    for (byte i = 0; i < mfrc522.uid.size; i++) {
      cardID.concat(String(mfrc522.uid.uidByte[i] < 0x10 ? "0" : ""));
      cardID.concat(String(mfrc522.uid.uidByte[i], HEX));
    }
    Serial.println(cardID);

    WiFiClient client;
    if (WiFi.status() == WL_CONNECTED) { // Check WiFi connection before sending
      HTTPClient http;
      String requestData = "cardID=" + cardID;  // Form the request data
      http.begin(client, serverAddress);  // Specify the server address
      http.addHeader("Content-Type", "application/x-www-form-urlencoded");  // Specify the content type
      int httpCode = http.POST(requestData);  // Send the POST request with the card ID

      if (httpCode > 0) {
        String response = http.getString();
        Serial.println("HTTP Response Code: " + String(httpCode));
        Serial.println("HTTP Response: " + response);

        // Process the server response here (if needed)

      } else {
        Serial.println("Error sending data to server. HTTP Error Code: " + String(httpCode));
      }

      http.end();
    } else {
      Serial.println("Failed to connect to server");
    }

    delay(1000);

    if (isSignedIn) {
      // Sign out User and reset Arduino
      Serial.print(userName);
      Serial.println(" has signed out.");
      Serial.println();
      isSignedIn = false;
      mfrc522.PICC_HaltA();  // Stop reading
      mfrc522.PCD_StopCrypto1();
      delay(1000);
      resetFunc(); // Call reset
    } else {
      // Sign in User
      Serial.print(userName);
      Serial.println(" has signed in.");
      Serial.println();
      isSignedIn = true;
      while (isSignedIn == true) {
        if (mfrc522.PICC_IsNewCardPresent() && mfrc522.PICC_ReadCardSerial()) {
          Serial.print(userName);
          Serial.println(" has signed out.");
          Serial.println();
          isSignedIn = false;
          mfrc522.PICC_HaltA();  // Stop reading
          mfrc522.PCD_StopCrypto1();
          delay(1000);
          resetFunc(); // Call reset
        }
      }
    }
  }
}

