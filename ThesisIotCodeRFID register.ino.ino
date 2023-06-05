#include <ESP8266WebServer.h>
#include <ESP8266HTTPClient.h>
#include <SPI.h>
#include <MFRC522.h>
#include <WiFiClient.h> // Add this line to include the WiFiClient library
//ADMIN SIDE
#define SS_PIN D2
#define RST_PIN D3
MFRC522 mfrc522(SS_PIN, RST_PIN);

const char* ssid = "Radiant[2.4G]"; //wifi ni sa ssob 2.4ghz lng 
const char* password = "@123456789abC";
//const char* ssid = "rey"; -----------------ga -1 ni sa ssob kn sa cellphone akon ni WIFI gamit
//const char* password = "a12345poco";
ESP8266WebServer server(80);

int readsuccess;
byte readcard[4];
char str[32] = "";
String StrUID;

void setup() {
  Serial.begin(115200);
  SPI.begin();
  mfrc522.PCD_Init();
  delay(500);

  WiFi.begin(ssid, password);
  Serial.println("");

  //----------------------------------------Wait for connection
  Serial.print("Connecting");
  while (WiFi.status() != WL_CONNECTED) {
    Serial.print(".");
    delay(250);
  }
  //----------------------------------------If successfully connected to the wifi router, the IP Address that will be visited is displayed in the serial monitor
  Serial.println("");
  Serial.print("Successfully connected to : ");
  Serial.println(ssid);
  Serial.print("IP address: ");
  Serial.println(WiFi.localIP());

  Serial.println("Please tag a card or keychain to see the UID !");
  Serial.println("");
}

void loop() {
  readsuccess = getid();

  if (readsuccess) {
    HTTPClient http;
    String UIDresultSend, postData;
    UIDresultSend = StrUID;

    //Post Data
    postData = "UIDresult=" + UIDresultSend;

    WiFiClient client; // Create a WiFiClient object
    http.begin(client, "http://192.168.1.11/admin_thesiss/getUID.php"); // Update the begin function call

    http.addHeader("Content-Type", "application/x-www-form-urlencoded");

    int httpCode = http.POST(postData);
    String payload = http.getString();

    Serial.println(UIDresultSend);
    Serial.println(httpCode);
    Serial.println(payload);

    http.end();
    delay(1000);
  }
}

int getid() {
  if (!mfrc522.PICC_IsNewCardPresent()) {
    return 0;
  }
  if (!mfrc522.PICC_ReadCardSerial()) {
    return 0;
  }

  Serial.print("THE UID OF THE SCANNED CARD IS : ");

  for (int i = 0; i < 4; i++) {
    readcard[i] = mfrc522.uid.uidByte[i];
    array_to_string(readcard, 4, str);
    StrUID = str;
  }
  mfrc522.PICC_HaltA();
  return 1;
}

void array_to_string(byte array[], unsigned int len, char buffer[]) {
  for (unsigned int i = 0; i < len; i++)
  {
    byte nib1 = (array[i] >> 4) & 0x0F;
    byte nib2 = (array[i] >> 0) & 0x0F;
    buffer[i * 2 + 0] = nib1 < 0xA ? '0' + nib1 : 'A' + nib1 - 0xA;
    buffer[i * 2 + 1] = nib2 < 0xA ? '0' + nib2 : 'A' + nib2 - 0xA;
  }
  buffer[len * 2] = '\0';
}
