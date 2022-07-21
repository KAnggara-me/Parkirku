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
    image = cv2.imread('logout/' + str(i) + 'b.jpeg')  # Nama gambar (test.jpg)
    print("Sending Image")
    regions = ['id']  # Change to your country
    with open('logout/' + str(i) + 'b.jpeg', 'rb') as fp:
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
    cv2.imwrite("out/"+str(plate)+"b.jpg", image)
    print("Image Moved to out/"+str(plate)+"b.jpg")
    update_status(plate, i)


def update_status(plate, i):
    myfiles = {'img': open("out/"+str(plate)+"b.jpg", 'rb')}
    data = {"plate": parsing(plate), "uid": "training"+str(i)}
    res = requests.post(
        'http://parkirku.apiwa.tech/api/update2',
        headers={'Authorization': 'Token 2aee723a0b2c2f11a4c9b4c2eefae6079a366bfb'},
        data=data,
        files=myfiles)
    print(res.status_code)


def main():
    for i in range(21, 22, 1):
        send_img(i)


if __name__ == "__main__":
    main()
