# Auto Deployment Workflow

This rule applies whenever the agent finishes making functional updates or bug fixes to the codebase.

## Workflow Requirements
1. **Auto-Commit and Push:**
   Immediately upon successfully completing an update, bug fix, or feature addition, the agent MUST automatically run `git add`, `git commit` (with a descriptive commit message), and `git push` to the repository. The agent does not need to ask for permission to commit and push unless explicitly told otherwise for a specific task.
2. **Post-Push Instructions for the User:**
   After pushing, the agent MUST provide a clear summary of the updates and explicit instructions on what the user needs to do to deploy these changes to their hosting environment. 
   
   The instructions must include, if applicable:
   - `git pull` commands.
   - Any dependency installation commands (e.g., `composer install`, `npm install`, `npm run build`) if new dependencies were added or assets modified.
   - Any database migration commands (e.g., `php artisan migrate`) if new migrations were created.
   - Cache clearing commands (e.g., `php artisan optimize:clear`, `php artisan config:cache`, `php artisan view:clear`) if config or views were changed.
   - Any other server-side operations required to make the update work and prevent errors on hosting.
