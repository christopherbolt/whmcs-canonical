# whmcs-canonical
A WHMCS hook for automatically adding a valid canonical url meta tag

WHMCS generates lots of content that can be accessed from multiple URLs and even URLs that can be manipulated.

For example the following URLs would all go to the same page:

/knowledgebase/1/real-news.html

/knowledgebase/1/

/knowledgebase/1/this-hosting-company-sucks.html

A canonical url meta tag tells search engines which URL is the correct one.
I also recommend adding open graph tags so that people cannot share mis-leading URLs on social media (like the this-hosting-company-sucks example above).
This hook adds a $canonical variable to templates that you could use to implement open graph url meta tags in your theme's header template. Implementation of open graph tags is outside the scope of this module but I have added an example in the installation section of this read me.

### Why I made this
All the code examples I've fount online for implementing canonical URLs in WHMCS rely on using the REQUEST_URI. This is a very bad idea because it doesn't solve the duplicate URL problem and allows the canonical url to be manipulated.

## Requirements
Tested with WHMCS version 8.12.1. I will not maintain support for older versions.

In WHMCS General Settings, Friendly URLs must be set to "Full Friendly Rewrite". This module is not designed to work with any of the other options.

For example: Your URLs must look something like https://www.yourcompany.com/knowledgebase/1/real-news.html

If your URLs look something like https://www.yourcompany.com/index.php?rb=knowledgebase/1/real-news.html then sorry this won't work.

## Installation
Always use the latest version from https://github.com/christopherbolt/whmcs-canonical/

Copy the includes/hooks/canonical.php file to your WHMCS installation.

### Adding Open Graph Tags
To implement open graph tags you could do something like this in your theme's includes/head.tpl file:

```html
{if $canonical}
{capture name="metatitle" assign="metatitle"}{if $kbarticle.title}{$kbarticle.title} - {elseif $templatefile == 'viewannouncement'}{$title} - {/if}{$pagetitle} - {$companyname}{/capture}

<meta property="og:url" content="{$canonical}">
<meta property="og:type" content="{if $kbarticle.title || $templatefile == 'viewannouncement'}article{else}website{/if}">
<meta property="og:title" content="{$metatitle}">
<meta property="og:image" content="YOUR_IMAGE_URL.png">

<meta property="twitter:card" content="summary_large_image">
<meta property="twitter:url" content="{$canonical}">
<meta property="twitter:title" content="{$metatitle}">
<meta property="twitter:image" content="YOUR_IMAGE_URL.png">
{/if}
```
