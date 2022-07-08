import threading
import time
import cv2
import requests


# print("Before URL")
cap = cv2.VideoCapture('rtsp://192.168.160.100:554/live/ch00_1')
cap.set(cv2.CAP_PROP_BUFFERSIZE, 3)  # set buffer size
# print("After URL")


def send_img():
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
    print(plate)
    move_img(plate)
    update_status(plate)


def move_img(plate):
    print("Moving Image")
    import shutil
    shutil.move("img/src/test.jpg", "img/out/"+str(plate)+".jpg")
    print("Image Moved to img/out/"+str(plate)+".jpg")


def update_status(plate):
    res = requests.post(
        'http://api.test/api/update',
        headers={'Authorization': 'Token 2aee723a0b2c2f11a4c9b4c2eefae6079a366bfb'},
        json={"plate": plate})
    print(res.status_code)


def check_status():
    response = requests.get('http://api.test/api/last')
    data = response.json()
    status = data["status"]
    print(status)
    return status


def main():
    int = 0
    while True:
        # print('About to start the Read command')
        ret, frame = cap.read()
        # print('About to show frame of Video.')
        cv2.imshow("Capturing", frame)
        # print('Running..')
        if cv2.waitKey(1) & 0xFF == ord('a'):
            cv2.imwrite('img/src/test.jpg', frame)
            send_img()
        elif cv2.waitKey(1) & 0xFF == ord('q'):
            break
        int += 1

        if int >= 60:
            status = check_status()
            if status == 1:
                cv2.imwrite('img/src/test.jpg', frame)
                send_img()
            int = 0

    cap.release()
    cv2.destroyAllWindows()


if __name__ == "__main__":
    main()
