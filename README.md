# LinkedIn SDK to setUp OAuth and manage campaign groups, campaigns, ads, ad targeting and social pages.



## Installation

You will need at least PHP 8.1.

Use composer package manager to install the lastest version of the package:
`composer require artisen2021/linkedinsdk`

Or add this package as dependency to `composer.json`
If you have never used Composer, you should start installing [composer](https://phptherightway.com/#composer_and_packagist).



## Get started

Before starting, it's important to read LinkedIn API Documentation.

In section My Apps, create a new developer application related to your LinkedIn Page.
Save the application `Client ID` and `Client Secret`.
Generate an `access token`.
Ensure you have the right permissions based on your use case.
Apply to the Marketing Developer Platform.

### Instantiate a client

```php
$client = new Client(
'YOUR_LINKEDIN_APP_CLIENT_ID',
'YOUR_LINKEDIN_APP_CLIENT_SECRET'
);
```

### Setting local redirect URL

Set a custom redirect url. This url is called when the user is connected to LinkedIn and redirected to your application.
```php
$this->linkedInClient->setRedirectUrl('https://your.domain/callback');
```

### Getting Login URL

In order to perform OAUTH 2.0 flow, you must direct the member's browser to LinkedIn's OAuth 2.0 authorization page where the member connects to LinkedIn, then either accepts or denies your application's permission request. 
To get redirect url to LinkedIn, use the following approach:
```php
$loginUrl = $client->getLoginUrl($scopes); 
```
Once the user is connected and has completed the authorization process, the browser is redirected to the URL provided in the redirect_uri query parameter and the Authorization Code appears in the URL.
This code is a value that you exchange with LinkedIn for an OAuth 2.0 access token.

### Getting Access Token

To get access token:
```php
$this->token = (new AccessTokenRequest($this->linkedInClient))->getAccessToken($code)->getToken();
```
In the AccessTokenRequest class, this token is stored in a file token.json:
```php
file_put_contents('token.json', json_encode($this->accessToken));
```

This AccessToken is used the header of the API calls.
```php
$header = ['Authorization' => 'Bearer ' . $this->token];
```

## Manage campaign groups

### Create a campaign group

```php
$campaignGroupRequest = new CampaignGroupRequest($this->linkedInClient, $this->accessTokenCode);
$campaignGroup =  $campaignGroupRequest->create([
     'account_id' => env('LINKEDIN_ACCOUNT_ID'),
     'name' => 'CampaignResources Group Test',
     'start' => 1672531200000,
     'end' => 1672617600000,
     'total_budget' => '100',
     'currency' => 'EUR'
]);
```

### Delete a campaign group

```php
$campaignGroupRequest = new CampaignGroupRequest($this->linkedInClient, $this->accessTokenCode);
$campaignGroupRequest->delete($campaignGroupId);
```

### Update a campaign group

```php
$campaignGroupRequest = new CampaignGroupRequest($this->linkedInClient, $this->accessTokenCode);
$campaignGroupRequest->update($campaignGroupId, $this->newParams);
```

## Manage campaigns

### Create a campaign 

```php
$campaignRequest = new CampaignRequest($this->linkedInClient, $this->accessTokenCode);
$campaign =  $campaignRequest->create([
      'account_id' => env('LINKEDIN_ACCOUNT_ID'),
      'campaign_group_id' => $campaignGroupId,
      'dailyBudget' => [
          'amount' => '20',
          'currencyCode' => 'EUR',
      ],
      'costType' => 'CPM',
      'country' => 'NL',
      'language' => 'nl',
      'name' => 'CampaignResources Test',
      'start' => 1672531200000,
      'end' => 1672617600000,
      'locations' => [
          "urn:li:geo:103644278"
      ],
      'type' => 'SPONSORED_UPDATES',
      'unitCost' => [
          'amount' => '30',
          'currencyCode' => 'EUR',
      ],
      'status' => 'ACTIVE'
]);
```

### Delete a campaign 

```php
$campaignRequest = new CampaignRequest($this->linkedInClient, $this->accessTokenCode);
$campaignRequest->delete($campaignId);
```

### Update a campaign 

```php
$campaignRequest = new CampaignRequest($this->linkedInClient, $this->accessTokenCode);
$campaignRequest->update($campaignId, $this->newParams);
```


## Manage ads

### Create an add (image add)

```php
        $adRequest = new AdRequest($this->linkedInClient, $this->accessTokenCode);
        $imageAd =  $adRequest->create([
            'account_id' => env('LINKEDIN_ACCOUNT_ID'),
            'linkedin_page_id' => env('LINKEDIN_PAGE_ID'),
            'campaign_id' => $campaignId,
            'media_type' => 'image',
            'message' => 'Image AdResources Test',
            'headline' => 'Image AdResources Test',
            'landing_page_url' => 'https://www.example.com/',
            'media_url' => 'https://www.example.com/image.jpg',
            'call_to_action' => 'attend',
        ]);
```

### Delete an add

```php
        $adRequest = new AdRequest($this->linkedInClient, $this->accessTokenCode);
        $adRequest->delete($adId);
```

### Update an add

```php
        $adRequest = new AdRequest($this->linkedInClient, $this->accessTokenCode);
        $adRequest->update($adId, [
            'account_id' => env('LINKEDIN_ACCOUNT_ID'),
            'linkedin_page_id' => env('LINKEDIN_PAGE_ID'),
            'campaign_id' => $campaignId,
            'media_type' => 'image',
            'message' => 'Image AdResources Updated Test',
            'headline' => 'Image AdResources Updated Test',
            'landing_page_url' => 'https://www.example.com/',
            'media_url' => 'https://www.example2.com/image.jpg',
            'call_to_action' => 'attend'
        ]);
```


## Manage ad targeting

### Fetch location
```php
$targetingRequest = new TargetingRequest($this->linkedInClient, $this->accessTokenCode);
$adFetched = $targetingRequest->fetchLocation($location);
```

### Fetch Urns
```php
$targetingRequest = new TargetingRequest($this->linkedInClient, $this->accessTokenCode);
$urns = $targetingRequest->fetchUrns($query,$facet);
```

### Fetch Similar
```php
$targetingRequest = new TargetingRequest($this->linkedInClient, $this->accessTokenCode);
$similars = $targetingRequest->fetchSimilar($urn,$facet);
```


## Manage social pages

### Get Pending Client Pages
```php
        $pendingPagesEvent = new LinkedInRequestPendingPagesEvent(env('LINKEDIN_PAGE_ID'));
        $socialPages = new SocialPageRequest($this->linkedInClient, $this->accessTokenCode);
        $pendingPages = $socialPages->getPendingClientPages($pendingPagesEvent);
```

### Get Page Data
```php
        $pageDataEvent = new LinkedInGetPageDataEvent(env('LINKEDIN_PAGE_ID'));
        $socialPages = new SocialPageRequest($this->linkedInClient, $this->accessTokenCode);
        $pageData = $socialPages->getPageData($pageDataEvent);
```

### Get Page Current Status
```php
        $pageCurrentStatusEvent = new LinkedInGetPageCurrentStatusEvent(env('LINKEDIN_PAGE_ID'));
        $socialPages = new SocialPageRequest($this->linkedInClient, $this->accessTokenCode);
        $pageCurrentStatus = $socialPages->getPageCurrentStatus($pageCurrentStatusEvent);
```

### Search Ad Accounts By Page Id
```php
        $adAccountsEvent = new LinkedInRequestAdAccountsEvent(env('LINKEDIN_PAGE_ID'));
        $socialPages = new SocialPageRequest($this->linkedInClient, $this->accessTokenCode);
        $adAccounts = $socialPages->searchAdAccountsByPageId($adAccountsEvent);
```
















