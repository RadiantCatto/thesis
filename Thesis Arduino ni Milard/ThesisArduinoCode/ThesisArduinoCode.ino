#include <Servo.h>
#include <Wire.h>
#include <LiquidCrystal_I2C.h>
#include <SoftwareSerial.h>

const int inductivePin = 9; // Connect output pin of the sensor to digital pin 9
void(* resetFunc) (void) = 0;     // Declare reset function at address 0
SoftwareSerial espSerial(2, 3);  // RX, TX
LiquidCrystal_I2C lcd(0x27, 16, 2); // Set the LCD address and dimensions

Servo servolid;
int user_points = 100;
bool executeCode = false; // Flag to control code execution

void setup() { 
  Serial.begin(9600);         // Initialize the serial communication for debugging
  espSerial.begin(9600);     // Initialize the software serial communication with NodeMCU
  servolid.attach(8); // Connected the Orange (SIGNAL) wire to D8
  pinMode(inductivePin, INPUT); // Set trig pin as output
  servolid.write(100); // Rotate servo to 90 degrees
  delay(1000); // Wait 2 seconds
  servolid.write(0); // Rotate servo back to original position
  delay(1000); // Wait 2 seconds
  /*
  lcd.begin(16, 2); // Initialize LCD
  lcd.setBacklight(HIGH); // Initialize backlight
  lcd.clear(); //Clear lcd screen
  //Welcoming Message
  lcd.setCursor(0, 0);
  lcd.print("Hello!");
  delay(2000);
  lcd.clear();
  lcd.setCursor(0, 0);
  lcd.print("Tap your card");
  lcd.setCursor(0, 1);
  lcd.print("to sign-in!"); */
}

void loop() {
  if (espSerial.available()) {
    String command = espSerial.readStringUntil('\n');
    if (command == "start") {
      Serial.println("Executing Code.");
      executeCode = true;
    } 
    else if (command == "stop") {
      Serial.println("Terminating Code.");
      executeCode = false;
      Serial.print("Sending points: ");
      Serial.println(user_points);
      String user_pointsString = String(user_points);
      espSerial.write(user_pointsString.c_str());
      delay(2000);     
      resetFunc(); // Call reset
    } 
    else {
      user_points = command.toInt();
      Serial.print("Received points: ");
      Serial.println(user_points);
      // Process the received points as needed
    }
  }
  if (executeCode) {
   // Read values from the sensors
  int inductiveValue = digitalRead(inductivePin);
  int IR100 = analogRead(A1);
  int IR65 = analogRead(A2);
  int IR35 = analogRead(A3);
  int wasteweight = 150; //placeholder for weight
  if (inductiveValue == HIGH) { // If the inductive sensor detects metal, servo motor won't open lid
      lcd.clear();
      lcd.setCursor(0, 0);
      lcd.print("Metal Detected!");
      lcd.setCursor(0, 1);
      lcd.print("Deposit Plastic!");
      delay(3000); 
    }
  else if (inductiveValue == LOW) { // If the inductive sensor doesn't detect metal, check for weight, then servo motor opens lid
    Serial.println("Welcome");
    lcd.clear();
    lcd.setCursor(0, 0);
    lcd.print("Welcome!");
    lcd.setCursor(0, 1);
    lcd.print("Deposit a bottle");
    delay(1000);
    
    if (IR35 < 500 && IR65 < 500 && IR100 < 500){
        Serial.println("Bin is Full");
        lcd.clear();
        lcd.setCursor(0, 0);
        lcd.print("Bin is Full");
        delay(1000);
        lcd.clear();
        lcd.setCursor(0, 0);
        lcd.print("Emptying");
        lcd.setCursor(0, 1);
        lcd.print("Scheduled");
        }
    else if (IR35 < 500 && IR65 < 500){
        Serial.println("Bin is 65% Full");
        lcd.clear();
        lcd.setCursor(0, 0);
        lcd.print("Bin is 65% Full");
        delay(3000);
        }
    else if (IR35 < 500){
        Serial.println("Bin is 35% Full");
        lcd.clear();
        lcd.setCursor(0, 0);
        lcd.print("Bin is 35% Full");
        delay(3000);
        }        
    else if(wasteweight < 200){
        Serial.println("Bottle Recorded");
        lcd.clear();
        lcd.setCursor(0, 0);
        lcd.print("Bottle Deposited");
        lcd.setCursor(0, 1);
        lcd.print("Successfully!");
        user_points = user_points + 10;
        delay(3000);
        lcd.clear();
        lcd.setCursor(0, 0);
        lcd.print("You currently");
        lcd.setCursor(0, 1);
        lcd.print("have ");
        lcd.print(user_points);    
        lcd.print(" points"); 
        servolid.write(100); // Rotate servo to 110 degrees
        delay(2000); // Wait 3 seconds
        servolid.write(0); // Rotate servo back to original position
        delay(2000); // Wait 3 seconds
        }
    else if(wasteweight > 200){
        Serial.println("Overweight!");
        lcd.clear();
        lcd.setCursor(0, 0);
        lcd.print("Non-Plastic");
        lcd.setCursor(0, 1);
        lcd.print("Detected!");
        delay(3000);
        lcd.clear();
        lcd.setCursor(0, 0);
        lcd.print("Please Deposit");
        lcd.setCursor(0, 1);
        lcd.print("Plastic Bottles!");
        delay(3000);  
      }  
    }  
  }
}