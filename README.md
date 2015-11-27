[![Build Status](https://travis-ci.org/andela-fokosun/Checkpoint3.svg)](https://travis-ci.org/andela-fokosun/Checkpoint3)

# EmojiforDevs REST API
:bowtie: :blush: :smirk: :satisfied: :scream: :stuck_out_tongue::bowtie: :blush: :smirk: :satisfied: :scream: :stuck_out_tongue::bowtie: :blush: :smirk: :satisfied: :scream: :stuck_out_tongue::bowtie: :blush: :smirk: :satisfied: :scream: :stuck_out_tongue::bowtie: :blush: :smirk: :satisfied: :scream: :stuck_out_tongue::bowtie: :blush: :smirk: :satisfied: :scream: :stuck_out_tongue::bowtie: :blush:

EmojiforDevs is a RESTful API that allows you **get**, **find**, **update** or **delete** any emoji of your choosing. The API employs the use of simple token based authentication.

##Usage

Working on a client project or on a side project? There are many fun stuff you can do with EmojiforDevs e.g.

**To have a listing of all the created emojis, you need to hit the `emojis` endpoint.**

So you can for example run a GET request in [Postman](https://www.getpostman.com/) like so:

        GET: https://emojisfordevs.herokuapp.com/emojis

        GET: https://emojisfordevs.herokuapp.com/emojis/1

Say you want emojis of a particular category, `skype` for example:

        GET: https://emojisfordevs.herokuapp.com/category/skype

output:

            [
                {
                    "id": 8,
                    "name": "Sad face",
                    "emojichar": "☹",
                    "keywords": "emotion",
                    "category": "skype",
                    "createdat": "2015-11-27 11:02:21",
                    "updatedat": "2015-11-27 11:15:10",
                    "createdby": "florence"
                },
                {
                    "id": 9,
                    "name": "Sad faces",
                    "emojichar": "☹☹",
                    "keywords": "emotions",
                    "category": "skype",
                    "createdat": "2015-11-27 11:48:01",
                    "updatedat": "2015-11-27 11:48:01",
                    "createdby": "florence"
                }
            ]

There you go, all the emojis under the **skype** category delivered to you in JSON format!

See [full documentation](https://emojifordevs.herokuapp.com) for the various endpoints and how to consume them.

##Contributing
This is an open-source project, so please feel free to tell family and friends to contribute to EmojiforDevs to make it even more awesome. [**see how**](https://github.com/andela-fokosun/Checkpoint3/wiki/Contributing)

**Happy Coding!**
