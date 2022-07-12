// Jangan ubah urutanya!!!
#include <WiFi.h>
#include <HTTPClient.h>
#include <Wire.h>
#include <Adafruit_PN532.h>

#include "auth.h"

// RFID Section
#define PN532_IRQ   (4)
#define PN532_RESET (15)  // Not connected by default on the NFC Shield
#define LED (2)

/////// please enter your sensitive data in the Secret tab/auth.h
/////// Wifi Settings ///////
const char* ssid = WIFI_SSID;
const char* pass = WIFI_PASS;

String serverName = "http://parkirku.apiwa.tech/api/login";
unsigned long lastTime = 0;
unsigned long timerDelay = 5000;

// Variabel Global
String tagId = "None";

// RFID Object
// Or use this line for a breakout or shield with an I2C connection:
Adafruit_PN532 nfc(PN532_IRQ, PN532_RESET);

void setup() {
  Serial.begin(115200);
  pinMode(LED, OUTPUT);

  wifi();
  init();
}

void wifi() {
  WiFi.begin(ssid, pass);
  Serial.println("Connecting");
  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }

  Serial.println("");
  Serial.print("Connected to WiFi network with IP Address: ");
  Serial.println(WiFi.localIP());
  Serial.println("Timer set to 5 seconds (timerDelay variable), it will take 5 seconds before publishing the first reading.");
}

void init() {
  nfc.begin();
  rfidInit();
}

void rfidInit() {
  uint32_t versiondata = nfc.getFirmwareVersion();
  if (! versiondata) {
    Serial.print("Didn't find PN53x board");
    while (1); // halt
  }
  // Got ok data, print it out!
  Serial.print("Found chip PN5"); Serial.println((versiondata >> 24) & 0xFF, HEX);
  Serial.print("Firmware ver. "); Serial.print((versiondata >> 16) & 0xFF, DEC);
  Serial.print('.'); Serial.println((versiondata >> 8) & 0xFF, DEC);

  // configure board to read RFID tags
  nfc.SAMConfig();

  Serial.println("Waiting for an ISO14443A Card ...");
}

void loop() {
  readRFID();
  delay(1000);
}

void readRFID() {
  uint8_t success;
  uint8_t uid[] = { 0, 0, 0, 0, 0, 0, 0 };  // Buffer to store the returned UID
  uint8_t uidLength;                        // Length of the UID (4 or 7 bytes depending on ISO14443A card type)

  success = nfc.readPassiveTargetID(PN532_MIFARE_ISO14443A, uid, &uidLength);

  if (success) {
    Serial.println("Found an ISO14443A card");
    // Display some basic information about the card
    tagId = "";
    for (byte i = 0; i <= uidLength - 1; i++) {
      tagId += (uid[i] < 0x10 ? "0" : "") + String(uid[i], HEX);
    }
    Serial.print("ID CARD : ");
    Serial.print(tagId);
    Serial.println("");
    checkWifi();
  }

  delay(1000);
}

void checkWifi() {
  //Check WiFi connection status
  if (WiFi.status() == WL_CONNECTED) {
    sendData();
  } else if (WiFi.status() != WL_CONNECTED) {
    Serial.println("Reconnecting to WiFi...");
    WiFi.disconnect();
    WiFi.reconnect();
  } else {
    Serial.println("WiFi Disconnected");
  }
}

void sendData() {
  WiFiClient client;
  HTTPClient http;

  // Your Domain name with URL path or IP address with path
  http.begin(client, serverName);

  // Specify content-type header
  http.addHeader("Content-Type", "application/x-www-form-urlencoded");
  // Data to send with HTTP POST
  String httpRequestData = "uid=" + tagId;
  Serial.println(httpRequestData);
  // Send HTTP POST request
  int httpResponseCode = http.POST(httpRequestData);

  Serial.print("HTTP Response code: ");
  Serial.println(httpResponseCode);

  if (httpResponseCode == 201)
  {
    digitalWrite(2, HIGH);
  } else {
    digitalWrite(2, LOW);
  }

  // Free resources
  http.end();
}
