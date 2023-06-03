#include <ESP8266WiFi.h>
#include <SPI.h>
#include <MFRC522.h>
#include <SoftwareSerial.h>

#define SS_PIN D3
#define RST_PIN D4

// Declaration of variables and other initializations
MFRC522 mfrc522(SS_PIN, RST_PIN);   // Create MFRC522 instance
SoftwareSerial arduinoSerial(4, 5);  //RX, TX
String userName = "User";          // Set user name
bool isSignedIn = false;            // Track sign-in status
void(* resetFunc) (void) = 0;     // Declare reset function at address 0

const char* ssid = "Redmi Note 9 Pro";
const char* password = "Milard123";

int user_points = 0; // Initialization of accumulated user points

void setup() {
  Serial.begin(9600);
  arduinoSerial.begin(9600);
  WiFi.begin(ssid, password);
  Serial.println();
  Serial.print("Connecting to ");
  Serial.println(ssid);
  while (WiFi.status() != WL_CONNECTED) {
    delay(1000);
    Serial.print(".");
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
    delay(1000);

    if (isSignedIn) {
      // Sign out User and send signal to Arduino to stop executing its code
      Serial.print(userName);
      Serial.println(" has signed out.");
      Serial.println();
      isSignedIn = false;
      mfrc522.PICC_HaltA();  // Stop reading
      mfrc522.PCD_StopCrypto1();
      delay(1000);
      arduinoSerial.write("stop");
      resetFunc(); // Call reset
    } else {
      // Sign in User and send signal to Arduino to start executing its code
      Serial.print(userName);
      Serial.println(" has signed in.");
      Serial.println();
      Serial.println(user_points);
      isSignedIn = true;
      arduinoSerial.write("start");   
      delay(2000);
      String user_pointsString = String(user_points);
      arduinoSerial.write(user_pointsString.c_str());
      while (isSignedIn == true) {
        if (mfrc522.PICC_IsNewCardPresent() && mfrc522.PICC_ReadCardSerial()) {
          Serial.print(userName);
          Serial.println(" has signed out.");
          Serial.println();
          isSignedIn = false;
          mfrc522.PICC_HaltA();  // Stop reading
          mfrc522.PCD_StopCrypto1();
          delay(1000);
          arduinoSerial.write("stop");
          resetFunc(); // Call reset
        }
        else if (arduinoSerial.available()) {
          String command = arduinoSerial.readStringUntil('\n');
          user_points = command.toInt();
          Serial.print("Received points: ");
          Serial.println(user_points);
          // Process the received points as needed
        }
      }
    }
  }
}