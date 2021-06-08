# OurSMS
### You can send SMS and OTP easily now


![image](https://github.com/hussein4alaa/oursms/blob/main/oursms.jpg)


## Installation:
Require this package with composer using the following command:

```sh
$ composer require capital/oursms
```

```sh
$ php artisan vendor:publish --provider=Capital\OurSms\OurSmsServiceProvider 
```

## Usage
##### in `.env` insert :
```sh
OUR_SMS_ID=your_id
OUR_SMS_KEY=your_key
```
you 'll find it in https://oursms.app

##### to send one sms :
```sh
use Capital\OurSms\Send;
.
.
.
Send::oneSms('07*********', 'Your Message');
```


##### to send one sms :
```sh
use Capital\OurSms\Send;
.
.
.
Send::otp('07*********', 'OTP expire time in minutes');
```


##### check OPT :
```sh
use Capital\OurSms\Send;
.
.
.
Send::checkOTP('07*********', 'OTP Code');
```


##### get message status :
```sh
use Capital\OurSms\Send;
.
.
.
Send::status('messageID');
```




##### Note :
```sh
You can use phone number format like that:
07*********
7*********
9647*********
```
### License

OutSms is free software licensed under the MIT license.
