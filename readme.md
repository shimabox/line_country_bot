# line_country_bot

## これは何

- LINEのMessaging APIを使ったbotです
- 国名を言われると国旗を出します
- クイズ と言われると国旗を使ったクイズを出します
  - quiz でも反応します

## 必要なもの

- PHP 5.6.4+ or newer
- [Composer](https://getcomposer.org)
- SSLサーバー
- Line Developer Trial アカウント
  - [Messaging APIのご紹介 | LINE Business Center](https://business.line.me/ja/services/bot "Messaging APIのご紹介 | LINE Business Center")

## インストール

```
$ git clone https://github.com/shimabox/line_country_bot.git
$ cd line_coutry_bot/
$ composer install
```

## 設定

```
$ cp .env.example .env
$ vim .env
```

```
APP_ENV=production
APP_DEBUG=false
APP_KEY=
APP_URL=your website url
APP_TIMEZONE=Asia/Tokyo

LINEBOT_API_ENDPOINT=your api endpoint
CHANNEL_SECRET=your api channel secret
CHANEL_ACCESS_TOKEN=your api access token

COUNTRY_DATA_CSV=<国情報を持つCSV配置パス>
NATIONAL_FLAG_IMG_PATH=<国旗画像配置ディレクトリパス>

PROFILE_IMG=assets/img/profile/your-profile-img
PROFILE_TITLE=your profile title
PROFILE_TEXT=your profile text
PROFILE_LINK_TEXT=your profile link text
PROFILE_LINK_URL=your profile link url
```

- COUNTRY_DATA_CSV
  - 国情報を持つCSVのパスを記述します
    - resources/ 以下のパスを見ます
  - 自分はこちらを利用させて頂きました
    - [世界の首都 World Capitals - ASTI アマノ技研](http://www.amano-tec.com/download/world.html "世界の首都 World Capitals - ASTI アマノ技研")
    ```
    code,namejp,namejps,nameen,namens,capitaljp,capitalen,lat,lon
    AD,アンドラ公国,アンドラ,Principality of Andorra,Andorra,アンドララベラ,Andorra la Vella,42.4919826,1.5111806
    ・
    ・
    ```
    - この形式(かつSJIS)であれば検索に引っかかると思います
  - csv/h2706world_sjis.csv と書けば、``` resources/csv/h2706world_sjis.csv ``` を参照します

- NATIONAL_FLAG_IMG_PATH
  - 国旗画像を配置するディレクトリのパスを記述します
  - 自分はこちらを利用させて頂きました
    - [Flags of all countries](http://flagpedia.net/ "Flags of all countries")
  - 画像ファイル名は **国コード(小文字).png** であればよいです
  - assets/img/flags/ と書けば、``` APP_URL/assets/img/flags/国コード(小文字).png ``` を参照します

- PROFILE_XXX
  - プロフィール情報を表示したい場合、PROFILE_IMGを設定し各定数を記述する必要があります
  - プロフィール情報を表示しない場合、PROFILE_IMGには何も設定しないでください
  - PROFILE_IMG
    - プロフィール画像
    - assets/img/profile/profile.png と書けば、``` APP_URL/img/profile/profile.png ``` を参照します
  - PROFILE_TITLE
    - プロフィールタイトル
  - PROFILE_TEXT
    - プロフィール説明文です
  - PROFILE_LINK_TEXT
    - プロフィール用URLで表示するテキストです
  - PROFILE_LINK_URL
    - プロフィール用URLです (twitterとか)

### おまけ

- 設定で ```PROFILE_XXX``` を適宜記載すれば、 君の名は と言われるとプロフィールを表示します
  - who でも反応します

## See Also

- [【Lumen】LINEの Messaging API を使って再度Botを作ったお話 | Shimabox Blog](https://blog.shimabox.net/2017/02/24/try_line_messaging_api/ "【Lumen】LINEの Messaging API を使って再度Botを作ったお話 | Shimabox Blog")

## License

- MIT License
