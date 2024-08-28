<p align="center"><a href="https://michaelsuch.co.uk" target="_blank"><img src="http://michaelsuch.co.uk/wp-content/uploads/2024/08/area-mapping-logo.png" width="400" alt="Area Mapping Logo"></a></p>

# Area Mapping

The Area Mapping tool is built with openlayers and allows you create, modify and save your geojson data. You can draw your own areas, upload a geojson file or add a geojson object directly to a form field. The data can be previewed and modified in realtime for a better user experience.

## Installation

Pre-requisites:

- Setup a PostgreSQL database locally with postGIS add-on.
- Development environment setup e.g Herd or Docker.
- Composer needs to be installed.

Clone the git files from laravel/area-mapping to a local repository.

cd into the repository then run the following commands:

Install dependencies:

```bash
composer install
```
Copy `.env.example` and create a `.env` file.

Generate an encryption key

```bash
php artisan key:generate
```
Add your postgreSQL database configuration to the `.env` file. Update the below details to match you configuration:

```bash
DB_CONNECTION=pgsql
DB_HOST=localhost
DB_PORT=5432
DB_DATABASE=postgis_map
DB_USERNAME=postgres
DB_PASSWORD=********
```
Run migrations:

```bash
php artisan migrate
```
Add Categories:

```bash
php artisan db:seed --class=CategorySeeder
```
Execute package scripts for development:

```bash
npm run dev
```
Once your local environment is up and running the site should now load

When your site is ready for production run the following command:

```bash
npm run dev
```
## Usage

To navigate the website first you need to login.

Once logged in you will be redirected to the `areas` page which will display a list of your areas once they're created.

### Create an area

- Click on the `ADD NEW AREA` button located to the top right of the areas list.
- You can either draw an area on the map or add geojson data to the form field by directly pasting geojson data into it or uploading a geojson file. A preview will be shown as soon as valid geojson data is added to the form field. 
- The final point of the area must overlap the start point to complete it. Once completed you can click inside the area to enable editing. The area lines can then be dragged and modified. 
- You then also need to include a `name`, `category` and `valid from` value in the form fields. `description` and `valid to` are optional.
- Once happy with the area details, hit `Submit` to save it. This will then redirect you back to the `areas` page and display the new area in the list.

### Edit an area

- Click the `EDIT` button in the areas list to take you to the `edit` page.
- Here the area will be prepopulated on the map and the form fields with their stored data.
- Click on the map to edit it and update any of the form fields if necessary.
- Once happy with the changes click `Submit` and you be redirected to the `areas` page with the updated details displaying in the list.




