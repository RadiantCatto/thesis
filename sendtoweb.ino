#include <ESP8266WiFi.h>
#include <ESP8266HTTPClient.h>
#include <WiFiClient.h>

#include <SoftwareSerial.h>
#include <ArduinoJson.h>


#include <Wire.h>


const char* ssid     = "Infinix NOTE 12";
const char* password = "244466666";
const char* serverName = "http://turbiditymonitoringsystem2.000webhostapp.com//post-esp-data.php";
String apiKeyValue = "waytawo";


SoftwareSerial nodemcu(D6, D5);


void setup() {
  Serial.begin(9600);
  nodemcu.begin(9600);
  while (!Serial) continue;

  WiFi.begin(ssid, password);
  Serial.println("Connecting");
  while(WiFi.status() != WL_CONNECTED) { 
    delay(500);
    Serial.print(".");
  }
  Serial.println("");
  Serial.print("Connected to WiFi network with IP Address: ");
  Serial.println(WiFi.localIP());

}

void loop() {
  
  StaticJsonBuffer<1000> jsonBuffer;
  JsonObject& data = jsonBuffer.parseObject(nodemcu);

  if (data == JsonObject::invalid()) {
    Serial.println("Invalid Json Object");
    jsonBuffer.clear();
    return;
  }

  Serial.println("JSON Object Recieved");
  Serial.print("Recieved:  ");
  String NTU = data["NTU"];
  String VOLT = data["VOLTS"];
  String REM = data["REMARKS"];
  Serial.println(NTU);
  Serial.println(VOLT);
  Serial.println(REM);
  Serial.println("-----------------------------------------");

  if(WiFi.status()== WL_CONNECTED){
    HTTPClient http;
    
    http.begin(serverName);
    http.addHeader("Content-Type", "application/x-www-form-urlencoded");
    String httpRequestData = "api_key=" + apiKeyValue + "&NTU=" + NTU
                          + "&VOLTS=" + VOLT + "&REMARKS=" + REM + "";
    Serial.println(httpRequestData);
    int httpResponseCode = http.POST(httpRequestData);
     
  if (httpResponseCode>0) {
      Serial.print("HTTP Response code: ");
      Serial.println(httpResponseCode);
    }
    else {
      Serial.print("Error code: ");
      Serial.println(httpResponseCode);
    }
    http.end();
  }
  else {
    Serial.println("WiFi Disconnected");
  }
  delay(2000);  
}
