import threading
import time
import cv2
from cv2 import imwrite
import requests


# print("Before URL")
cap = cv2.VideoCapture('rtsp://192.168.1.5:554/live/ch00_1')
cap.set(cv2.CAP_PROP_BUFFERSIZE, 3)  # set buffer size
# print("After URL")


def send_img():
    image = cv2.imread('img/src/test.jpg')  # Nama gambar (test.jpg)
    print("Sending Image")
    regions = ['id']  # Change to your country
    with open('img/src/test.jpg', 'rb') as fp:
        response = requests.post(
            'https://api.platerecognizer.com/v1/plate-reader/',
            data=dict(regions=regions),  # Optional
            files=dict(upload=fp),
            headers={'Authorization': 'Token 2aee723a0b2c2f11a4c9b4c2eefae6079a366bfb'})
        data = response.json()
        plate = data['results'][0]['plate']
        x1 = data['results'][0]['box']['xmin']
        x2 = data['results'][0]['box']['xmax']
        y1 = data['results'][0]['box']['ymin']
        y2 = data['results'][0]['box']['ymax']
        # Blue color in BGR
    plt = parsing(plate)
    image = cv2.rectangle(image, (x1, y1), (x2, y2), (0, 0, 255), 2)
    image = cv2.putText(image, plt, (x1, y1-30),
                        cv2.FONT_HERSHEY_SIMPLEX, 1, (0, 255, 0), 2)
    cv2.imwrite("img/out/"+str(plate)+".jpg", image)
    print("Image Moved to img/out/"+str(plate)+".jpg")
    update_status(plate)


def parsing(plate):
    plate = plate.upper()
    province = plate[0:2]
    number = plate[2:6]
    region = plate[6:len(plate)]
    plate = province + " " + number + " " + region
    print(plate)
    return plate


def update_status(plate):
    myfiles = {'img': open("img/out/"+str(plate)+".jpg", 'rb')}
    data = {"plate": parsing(plate)}
    res = requests.post(
        'http://parkirku.apiwa.tech/api/update2',
        headers={'Authorization': 'Token 2aee723a0b2c2f11a4c9b4c2eefae6079a366bfb'},
        data=data,
        files=myfiles)
    print(res.status_code)


def check_status():
    response = requests.get('http://parkirku.apiwa.tech/api/last')
    data = response.json()
    status = data["status"]
    print(status)
    return status


def main():
    int = 0
    while True:
        ret, frame = cap.read()
        # image = cv2.imread('test.jpg')  # Nama gambar (test.jpg)
        cv2.imshow("Capturing", frame)
        if cv2.waitKey(1) & 0xFF == ord('a'):
            cv2.imwrite("img/src/test.jpg", frame)
            send_img()
        elif cv2.waitKey(1) & 0xFF == ord('q'):
            break
        int += 1

        if int >= 60:
            status = check_status()
            if status == 3:
                cv2.imwrite("img/src/test.jpg", frame)
                send_img()
            int = 0

    cap.release()
    cv2.destroyAllWindows()


if __name__ == "__main__":
    main()
