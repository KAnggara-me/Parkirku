#include <WiFi.h>
#include <HTTPClient.h>

#include <Wire.h>
#include <Adafruit_PN532.h>

#include "auth.h"

#define PN532_IRQ   (4)
#define PN532_RESET (15)  // Not connected by default on the NFC Shield

const char* ssid = WIFI_SSID;
const char* password = WIFI_PASS;

//Your Domain name with URL path or IP address with path
String serverName = "http://parkirku.apiwa.tech/api/login";

// Or use this line for a breakout or shield with an I2C connection:
Adafruit_PN532 nfc(PN532_IRQ, PN532_RESET);

String idcard;
void setup() {
  Serial.begin(115200);
  pinMode(2, OUTPUT);

  wifi();
  rfidInit();

}

void rfidInit() {
  nfc.begin();

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

void wifi() {
  WiFi.begin(ssid, password);
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

void loop() {
  readUID();
  delay(2000);
}

void readUID(void) {
  uint8_t success;
  uint8_t uid[] = { 0, 0, 0, 0, 0, 0, 0 };  // Buffer to store the returned UID
  uint8_t uidLength;                        // Length of the UID (4 or 7 bytes depending on ISO14443A card type)

  success = nfc.readPassiveTargetID(PN532_MIFARE_ISO14443A, uid, &uidLength);

  if (success) {
    Serial.println("Found an ISO14443A card");
    // Display some basic information about the card
    idcard = "";
    for (byte i = 0; i <= uidLength - 1; i++) {
      idcard += (uid[i] < 0x10 ? "0" : "") +
                String(uid[i], HEX);
    }
    Serial.print("ID CARD : ");
    Serial.print(idcard);
    Serial.println("");
    sendData();
  }
}

void sendData() {
  //Check WiFi connection status
  if (WiFi.status() == WL_CONNECTED) {
    WiFiClient client;
    HTTPClient http;

    // Your Domain name with URL path or IP address with path
    http.begin(client, serverName);

    // Specify content-type header
    http.addHeader("Content-Type", "application/x-www-form-urlencoded");
    // Data to send with HTTP POST
    String httpRequestData = "uid=" + idcard;
    Serial.println(F("Send Data"));
    // Send HTTP POST request
    int httpResponseCode = http.POST(httpRequestData);

    Serial.print("HTTP Response code: ");
    Serial.println(httpResponseCode);

    if (httpResponseCode == 201) {
      digitalWrite(2, HIGH);
      openGate();
    } else {
      digitalWrite(2, LOW);
      Serial.println(F("Buzzer ON"));
    }

    // Free resources
    http.end();
  } else {
    Serial.println(F("WiFi Disconnected"));
  }
}

void openGate() {
  Serial.println(F("Open Gate"));
}

void closeGate() {
  Serial.println(F("Close Gaate"));
}
