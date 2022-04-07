#!/usr/bin/env sh

# wait for MSSQL server to start
export STATUS=1
i=0

while [[ $STATUS -ne 0 ]] && [[ $i -lt 30 ]]; do
	i=`expr $i + 1`
	
        ### -t 1 --- query time out
	### -l 2 --- login timeout
	/opt/mssql-tools/bin/sqlcmd -t 1 -l 2 -S ${DB_HOST},${DB_PORT:-1433} -U ${DB_USERNAME} -P ${DB_PASSWORD} -d ${PUBLIC_API_DB_NAME} -Q "select 1" >> /dev/null
	STATUS=$?
done

if [[ $STATUS -ne 0 ]]; then
	echo "Error: MSSQL SERVER took more than 60 seconds to start up."
	exit 1
fi

echo "MSSQL Works"
