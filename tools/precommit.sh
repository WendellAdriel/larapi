echo Updating API docs...
sh ./tools/swagger.sh
git add ./public/swagger

git status --porcelain | grep -e '^[AM]\(.*\).php$' | cut -c 3- | while read line; do
    echo Linting PHP code on file "$line"...
    ./vendor/bin/php-cs-fixer fix "$line" --using-cache=false --rules=@PSR2
    echo Updating file to commit...
    git add "$line"
done

echo Committing as $(git config user.name)
echo DONE! Proceeding to commit
