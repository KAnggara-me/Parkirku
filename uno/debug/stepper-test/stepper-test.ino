#include <Stepper.h>

const int steps_per_rev = 60; //Set to 200 for NIMA 17 360º
// Set 50 for 90º
#define IN1 14
#define IN2 27
#define IN3 26
#define IN4 25
#define RL 2

Stepper motor(steps_per_rev, IN1, IN2, IN3, IN4);


void setup()
{
  pinMode(RL, OUTPUT);
  motor.setSpeed(75);
  Serial.begin(115200);
  digitalWrite(RL, LOW);
}

void loop()
{
  buka();
  tutup();
}

void buka() {
//  digitalWrite(RL, LOW);
  Serial.println("Rotating Anti-clockwise...");
  motor.step(-steps_per_rev);
//  digitalWrite(RL, HIGH);
  delay(5000);
}

void tutup() {
//  digitalWrite(RL, LOW);
  Serial.println("Rotating Clockwise...");
  motor.step(steps_per_rev);
//  digitalWrite(RL, HIGH);
  delay(5000);
}
