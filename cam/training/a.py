import requests


def login(i):
    data = {"uid": "training"+str(i)}
    res = requests.post(
        'http://parkirku.apiwa.tech/api/login',
        data=data)
    print(res.status_code)


def main():
    for i in range(1, 3, 1):
        login(i)


if __name__ == "__main__":
    main()
