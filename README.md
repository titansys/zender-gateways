# Zender Gateway Controllers

![Status](https://img.shields.io/badge/status-released-blue?style=for-the-badge)

🔩 Zender gateway controller files for different providers

# Available Controllers

| Gateway | Status |
| ------ | ------ |
| [Twilio](https://www.twilio.com/) | Released | Titan Systems |
| [Ultimate SMS](https://codecanyon.net/item/ultimate-sms-bulk-sms-application-for-marketing/20062631) | Released | Titan Systems |

# Usage

* Open and edit the controller file using your text editor
* Change the indicated required provider credentials then save
* Go to zender admin panel then to Gateways -> Add Gateway
* Enter the gateway name that will appear in the send message form
* Select the controller file you edited from the previous steps
* Configure your pricing table for the gateway, format explanation can be located below
* Submit and your new external gateway is ready

![App](https://github.com/titansys/zender-gateways/blob/master/screenshot.png)

# Pricing

The custom gateway system only supports sending function, you can also specify pricing of each sent sms by country which will be deducted from user credits. It will use the currency you selected in the system settings.

**default** - The default price for each sms, if the country of recipient is not listed in **countries** array then default price will be used for credit deduction.

**countries** - The array of countries with unique pricing, if you want to charge more for sending to specific country, you can do it here. Please note that country codes must be in **ISO Alpha-2** format. Prices supports float values ie..,, 0.002, 0.01 and so on.

Please strictly follow this format:
```yaml
{
  "default": "0.01",
  "countries": {
    "us": "0.01",
    "ph": "0.1",
    "gb": "0.02"
  }
}
```

# Gateway Request

If you want to integrate a custom sms gateway, you can easily do that by using the **boilerplate.php** file in this repository, this requires basic PHP knowledge. Alternatively, we might also integrate it for you, please post to our support site.
