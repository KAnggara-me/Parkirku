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


def main():
    print("Sending Image")
    image = cv2.imread('4.jpg')  # Nama gambar (test.jpg)
    regions = ['id']  # country code
    with open('4.jpg', 'rb') as fp:  # Nama gambar (test.jpg)
        response = requests.post(
            'https://api.platerecognizer.com/v1/plate-reader/',
            data=dict(regions=regions),  # Optional
            files=dict(upload=fp),
            headers={'Authorization': 'Token 2aee723a0b2c2f11a4c9b4c2eefae6079a366bfb'})
        data = response.json()
        plate = parsing(data['results'][0]['plate'])
        x1 = data['results'][0]['box']['xmin']
        x2 = data['results'][0]['box']['xmax']
        y1 = data['results'][0]['box']['ymin']
        y2 = data['results'][0]['box']['ymax']
        # Blue color in BGR
    image = cv2.rectangle(image, (x1, y1), (x2, y2), (0, 0, 255), 2)
    image = cv2.putText(image, plate, (x1, y1-30),
                        cv2.FONT_HERSHEY_SIMPLEX, 1, (0, 255, 0), 2)

    cv2.imshow("Hasil", image)
    cv2.waitKey(0)


if __name__ == "__main__":
    main()
