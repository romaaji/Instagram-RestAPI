[![Known Vulnerabilities](https://snyk.io//test/github/abehbatre/MDVK-Kit/badge.svg?targetFile=mdvklibrary/build.gradle)](https://snyk.io//test/github/abehbatre/MDVK-Kit?targetFile=mdvklibrary/build.gradle)

# Instagram-Downloader-API
new feature : support multiple image (bulk)

#### using example :


1.) make sure `xampp / lampp` installed in your fucking machine .

2.) clone project to `../../htdocs` **(htdocs folder)**

3.) `http://127.0.0.1/instagram-downloader-api/?url=https://www.instagram.com/p/Byy39R5FVUt/?utm_source=ig_web_copy_link`

#### output :
```json
{
    "code": 200,
    "type": "bulk-image",
    "author": "kabaraceh",
    "caption": "Kabar Aceh Indonesia on Instagram: “Kecelakaan Sempati Star vs Xenia, 6 Orang Meninggal Dunia  ACEH TIMUR – Enam orang dilaporkan meninggal dunia dalam kecelakaan lalu lintas…”",
    "like": "17.2k",
    "comment": "417",
    "images_url": [
        {
            "0": "https://instagram.fcgk13-1.fna.fbcdn.net/vp/043c82b0cb6f51dd374ca30c271d2116/5DA00C02/t51.2885-15/e35/61710933_441011100028356_1200308344843473952_n.jpg?_nc_ht=instagram.fcgk13-1.fna.fbcdn.net",
            "2": "https://instagram.fcgk13-1.fna.fbcdn.net/vp/2e13abd5c13663cda3e25ef9264946db/5D8D43CC/t51.2885-15/e35/62259939_496632521075600_2060760074525648447_n.jpg?_nc_ht=instagram.fcgk13-1.fna.fbcdn.net"
        }
    ],
    "total_record": 2
}
```

#### Dev :
 * @author    ren <ren_ice@live.com>
 * @author    [adit.web.id](https://adit.web.id)
