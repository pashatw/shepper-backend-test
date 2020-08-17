# :pencil: Shepper Backend Developer Test

## :book: Background
<img align="right" width="220" src="https://github.com/shepper-tech/shepper-backend-test/blob/master/app_preview.png?raw=true">

Shepper performs inspections on properties and assets for a wide variety of businesses ranging from property condition checks to customer experience checks and retail audits, all carried out by our gig economy workers (called Shepherds) through filling out a checklist of questions via our mobile app.

Shepherds define a list of locations as part of the onboarding when they install the mobile app. The locations allow them to define areas (a set of coordinates and a radius) they want to work in which enables us to show them jobs within those areas. These locations may be their home, where they work or anywhere else they frequently visit.

When a Shepherd tries to add a new area, we first validate that the given coordinates are within the right country for the Shepherd, and then we try to assign a label for the given coordinates as a user-friendly reference. For example, if we were given the coordinates `51.499479, -0.085499` the label would show as `London, UK`.

Shepherds can also edit and remove these locations as required.

## :trophy: Challenge
We would like you to build a simple API to allow users to manage their stored locations. The core requirements are as follows:

- Users should be able to view, create, update and delete their own stored locations.
- When creating a new location, the coordinates should be validated to ensure they're within the user's own country.
- When saving a new location, a user-friendly label should be generated based on the given coordinates.
- Users should only be able to have a max of five locations.
- Users should not be able to view, edit or delete eachother's locations.

### Endpoints
- `GET /user` _(already implemented)_
  - Returns the currently authenticated user.

- `GET /locations`
  - Returns an array of the user's current locations.

- `POST /locations`
  - Allows a new location to be created, and returns the new location. The following fields are required:

    - `title` (between 3 and 30 characters)
    - `latitude`
    - `longitude`
    - `radius` (between 0.5 and 50km)

- `PUT /locations/{id}`
  - Allows an existing location for the user to be updated by ID, returning the adjusted location. The following fields are optional, meaning none could be provided and nothing would change, or just the title could be given and only the title would be updated.

    - `title` (between 3 and 30 characters)
    - `latitude`
    - `longitude`
    - `radius` (between 0.5 and 50km)

- `DELETE /locations/{id}`
  - Allows an existing location for the user to be deleted by ID. An empty `204 - No Content` response should be returned.
  
### Geolocation Service
Integrating with some sort of service to provide geolocation is outside of the scope of the test, so we've provided a simple `GeolocationService` interface which provides methods for identifying whether or not a set of coordinates are within a given country, and a method to get a label for a set of coordinates.

You'll need to complete the `LocalGeolocationService` implementation using the given information about specific coordinates:

- `51.499479, -0.085499`
  - London, GB
  
- `52.486059, -1.891002`
  - Birmingham, GB
  
- `53.799102, -1.548120`
  - Leeds, GB
  
- `48.852774, 2.345620`
  - Paris, FR

- `50.109852, 8.681891`
  - Frankfurt, DE
  
For any other coordinates given that don't match the set above, the provided `InvalidCoordinatesException` should be thrown and handled appropriately.

### Location Response Format

Let's pretend we're building these endpoints to a specification for an app, so please ensure any time a location object is returned from the API, it follows this JSON format precisely:

```
{
  "name": "Home",
  "label": "Frankfurt, DE",
  "latitude": "50.109852",
  "longitude": "8.681891",
  "radius": 25.0
}
```

## :eyes: Pointers
We're most interested in the following areas:

- Your use of applicable Laravel functionality and how you structure your code and validate the requests.
- Your approach to testing the solution for correctness according to the acceptance criteria, and the types of testing you decide to use.

We're also keen to see strict adherance to a particular coding style of your choice (we use PSR12).

If you run low on time we'd rather see one well implemented endpoint (such as the "Add Location" endpoint) with really good test coverage as opposed to all endpoints with light or no test coverage.

## :postbox: Submission
Feel free to fork this repository and once you're happy with your work just add me ([@nickma42](https://github.com/nickma42)) as a contributor.

Please include all necessary build steps and instructions required to run and validate your work.
