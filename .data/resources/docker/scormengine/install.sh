#!/bin/sh

cd /opt/ScormEngine/Installer
java -Dlogback.configurationFile=logback.xml -cp "lib/*" RusticiSoftware.ScormContentPlayer.Logic.Upgrade.ConsoleApp mysql "jdbc:mysql://${RDS_HOST}/${RDS_NAME}?user=${RDS_USER}&password=${RDS_PASS}|com.mysql.jdbc.Driver"
java -Dlogback.configurationFile=logback.xml -cp "lib/*" RusticiSoftware.ScormContentPlayer.Logic.Upgrade.ConsoleApp EngineInstall.xml
