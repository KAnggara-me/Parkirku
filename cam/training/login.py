import cv2
import requests


def parsing(plate):
    plate = plate.upper()
    province = plate[0:2]
    number = plate[2:6]
    region = plate[6:len(plate)]
    plate = province + " " + number + " " + region
    print(plate)
    return plate


def send_img(i):
    image = cv2.imread('login/' + str(i) + 'a.jpeg')  # Nama gambar (test.jpg)
    print("Sending Image")
    regions = ['id']  # Change to your country
    with open('login/' + str(i) + 'a.jpeg', 'rb') as fp:
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
    cv2.imwrite("out/"+str(plate)+".jpg", image)
    print("Image Moved to out/"+str(plate)+".jpg")
    update_status(plate)


def update_status(plate):
    myfiles = {'img': open("out/"+str(plate)+".jpg", 'rb')}
    data = {"plate": parsing(plate)}
    res = requests.post(
        'http://parkirku.apiwa.tech/api/update',
        headers={'Authorization': 'Token 2aee723a0b2c2f11a4c9b4c2eefae6079a366bfb'},
        data=data,
        files=myfiles)
    print(res.status_code)


def login(i):
    data = {"uid": "training"+str(i)}
    res = requests.post(
        'http://parkirku.apiwa.tech/api/login',
        data=data)
    print(res.status_code)


def main():
    for i in range(1, 12, 1):
        login(i)
        send_img(i)


if __name__ == "__main__":
    main()
