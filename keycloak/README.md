# Friend Finder - Keycloak

<!-- TOC -->

- [Friend Finder - Keycloak](#friend-finder---keycloak)
    - [First-time setup](#first-time-setup)

<!-- /TOC -->

## First-time setup
The following steps must be followed when setting up Keycloak for the first time:

1. Create a new realm called "friend-finder".
2. Within the "friend-finder" realm, create a new OpenID connect provider.
    - General settings:
        - Client ID: "friend-finder"
        - Name: "Friend Finder"
    - Access settings:
        - Root URL: "http://127.0.0.1:8100/"
        - Home URL: "http://127.0.0.1:8100/"
        - Valid redirect URIs: "http://127.0.0.1:8100/*"
        - Web origins:
          - "http://127.0.0.1:8100"
3. Within "Realm settings" (see sidebar -> Configure), go to the "Login" tab and set "User registration" to "On".
4. Within "Authentication" (see sidebar -> Configure), go to the "Required actions" tab. Search for "Delete account" and enable it.
5. Within "Realm roles" (see sidebar -> Manage), click on the realm name "default-roles-friend-finder", then click on the "Assign role" button, select "Filter by clients" inside the filter dropdown, select "delete-account" and "manage-users", then click on the "Assign" button.

If you want to be able to run the frontend application separate from the docker environment, make sure to add "http://127.0.0.1:8080/*" to the valid redirect URIs and "http://127.0.0.1:8080" to the web origins.
