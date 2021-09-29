#!/usr/bin/env bash

BRANCH_BEGINNING="feature/\|hotfix/\|bugfix/\|dependabot/"
COMMIT_BEGINNING="feat\|build\|ci\|docs\|fix\|perf\|refactor\|style\|test\|refactor"
SPECIALS="Bump\|Sync"
target=$1
gitbranch=$2
branch_name_check() {
    messagecheck=$(echo $gitbranch | grep $BRANCH_BEGINNING)
    if [ -z "$messagecheck" ]; then
        echo "Your branch name must begin with one of the following"
        echo "$BRANCH_BEGINNING"
        echo " "
        exit 1
    fi
}

commit_message_check() {
    # Get the current branch and apply it to a variable
    currentbranch=$(git branch | grep \* | cut -d ' ' -f2)

    #Gets the commits for the current branch and outputs to file
    git log --pretty=format:"%H" $(git merge-base origin/$target origin/$currentbranch)..origin/$currentbranch >shafile.txt

    # loops through the file an gets the message
    for i in $(cat ./shafile.txt); do
        # gets the git commit message based on the sha
        gitmessage=$(git log --format=%B -n 1 "$i")

        messagecheck=$(echo $gitmessage | grep -w $SPECIALS)
        if [ -z "$messagecheck" ]; then

            if [ "${gitmessage,,}" != "$gitmessage" ]; then
                echo "Your commit message must contain only lowercase letters"
                echo " "
                messagecheck2=""
            fi

            messagecheck=$(echo $gitmessage | grep -w $COMMIT_BEGINNING)
            if [ -z "$messagecheck" ]; then
                echo "Your commit message must begin with one of the following"
                echo "  $COMMIT_BEGINNING(feature-name)"
                echo " "
            fi
            messagecheck=$(echo $gitmessage | grep ": ")
            if [ -z "$messagecheck" ]; then
                echo "Your commit message has a formatting error please take note of special characters '():' position and use in the example below"
                echo "   type(some txt): some txt"
                echo " "
            fi

            # All checks run at the same time by pipeing from one grep to another
            messagecheck=$(echo $gitmessage | grep -w $COMMIT_BEGINNING | grep ": ")

        fi

        # check to see if the messagecheck var is empty
        if [ -z "$messagecheck" -o -z "$messagecheck2" ]; then
            echo "The commit message with sha: '$i' failed "
            echo "Please review the following :"
            echo " "
            echo $gitmessage
            echo " "
            rm shafile.txt >/dev/null 2>&1
            exit 1
        else
            echo "$messagecheck"
            echo "'$i' commit message passed"
        fi
    done
    rm shafile.txt >/dev/null 2>&1
}

messagecheck2="valid"

# Calling the function
branch_name_check
commit_message_check
