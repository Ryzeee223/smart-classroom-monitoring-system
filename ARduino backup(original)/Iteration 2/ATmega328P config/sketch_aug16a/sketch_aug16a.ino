//ATmega code swtch 3-4 ON
#include <SPI.h>
#include <MFRC522.h>
#include <LiquidCrystal_I2C.h>

#define SS_PIN 10
#define RST_PIN 9
#define BUZZER_PIN 4  // Buzzer positive pin connected to Pin 4

LiquidCrystal_I2C lcd(0x27, 20, 2);
MFRC522 mfrc522(SS_PIN, RST_PIN);

void setup() {
  Serial.begin(115200); 
  SPI.begin();
  mfrc522.PCD_Init();

  pinMode(BUZZER_PIN, OUTPUT);
  digitalWrite(BUZZER_PIN, LOW);

  lcd.init();
  lcd.backlight();
  lcd.setCursor(0, 0);
  lcd.print("Scan RFID Card..");
}

void loop() {
  // Look for new cards
  if (!mfrc522.PICC_IsNewCardPresent() || !mfrc522.PICC_ReadCardSerial()) {
    delay(50);
    return;
  }
  
  // Format UID string
  String cardUID = "";
  for (byte i = 0; i < mfrc522.uid.size; i++) {
    if (mfrc522.uid.uidByte[i] < 0x10) cardUID += "0";
    cardUID += String(mfrc522.uid.uidByte[i], HEX);
  }
  cardUID.toUpperCase();

  // Audio feedback: 2000Hz beep for 100ms
  tone(BUZZER_PIN, 2000, 100);

  // Visual feedback on LCD
  lcd.clear();
  lcd.setCursor(0, 0);
  lcd.print("Tag Scanned!");
  lcd.setCursor(0, 1);
  lcd.print("UID: " + cardUID);

  // Send UID over Serial to ESP8266
  Serial.println(cardUID);
  
  mfrc522.PICC_HaltA();
  
  // Cooldown delay before resetting display prompt
  delay(3000); 
  lcd.clear();
  lcd.setCursor(0, 0);
  lcd.print("Scan RFID Card..");
}