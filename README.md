CloudFlare Sample App - Subscription Handler API
=================================================

This is an example of an API with a set of endpoints to handle subscriptions for
a CloudFlare App.

Included:

   1. API endpoints based on CloudFlare docs: http://appdev.cloudflare.com/docs/api-calls.html
   2. Apache configuration to handle RESTful API endpoint.

**NOTE:**

   As this is just a sample, it does not include a database connection, nor actual data. All endpoints
   spit out dummy JSON and the business logic and database part of the application are not actually present.
   In an actual API, you'll need your application/business logic and database layers to keep track of the
   users/subscriptions.
