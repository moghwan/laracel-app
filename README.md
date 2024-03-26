### A Laravel / Vercel boilerplate template with php 8.3
This project will help you get started with a new Laravel project hosted on Vercel. It includes a basic setup for a Laravel project with a Vercel deployment pipeline. The project is setup to use:
- PHP 8.3
- Laravel 11
- Supabase as a database engine
- GitHub actions for deployment

The goal of this project is to provide a quick starting point as an alternative to (my case) DigitalOcean to run little projects with no cost.

### Setup
- create a new GitHub project using [this template repo](https://github.com/new?template_name=laracel-app&template_owner=moghwan)

### Getting your secrets
- clone your repo and create a laravel app key:
  - `php artisan key:generate`
- Vercel: link your GitHub repo
  - type: other
  - deploy (it will fail and it's okay)
- get your Vercel token & project/organization ids:
  - https://vercel.com/moghwan/[YOUR-PROJECT]/settings > Project ID
    - change node js version to 18
  - https://vercel.com/account > Vercel ID (organization id)
  - https://vercel.com/account/tokens > create a new token (name it `VERCEL_LARACEL_TOKEN` for example)
- Supabase: create a new project, password will be needed
  - go to connect > copy uri 
    
### Setting your secrets
- Vercel: settings > environment variables
  - `DB_URL`: copied uri, replace `[YOUR-PASSWORD]` with your Supabase project password
  - `APP_KEY`: project id
- GitHub: settings > secrets & variables > actions > new repository secret
  - `DB_URL`: copied uri, replace `[YOUR-PASSWORD]` with your Supabase project password
  - `VERCEL_LARACEL_TOKEN`: copied token
  - `VERCEL_ORG_ID`: copied organization id
  - `VERCEL_PROJECT_ID`: copied project id
- GitHub: actions > redeploy last action

### Keep in mind
- Deployments are done with GitHub Actions and Vercel cli, not Vercel's auto deploy.
- Deployment via Vercel cli are run with the flag --force to avoid using cache, notably the `composer.lock` file.
- Database seeder are run with every deployment for demo purposes, disable it by commenting this line `.github/workflows/main.yml:26`
- This is a **fresh Laravel 11** install, these following changes are made as needed:
  - `api/index.php` - the entry point for the Vercel serverless function.
  - `dist/` - an empty folder for the default Vercel deployment output.
  - `.github/workflows/main.yml` - a GitHub Actions workflow file to deploy the app to Vercel.
  - `vercel.json` - a Vercel configuration file to define the serverless function and the php runtime.
  - `database/seeders/DatabaseSeeder.php` - using User factory to seed users table with data.
  - API endpoints are prefixed with `/backend` instead of `/api`.
- I avoided using sqlite for the database because it's not supported for writing by Vercel's serverless functions.
