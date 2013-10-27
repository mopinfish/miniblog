# /bin/zsh
# このスクリプト(setup.sh)のディレクトリの絶対パスを取得
DIR=$(cd $(dirname $0); pwd)

# simply frameworkのセットアップ
rm $DIR/app/core
rm $DIR/app/vendor

cd ..
git clone git@github.com:mopinfish/simply.git
pwd
ln -s "../../simply/application/core" ${DIR}/app/core
ln -s "../../simply/vendor" ${DIR}/app/vendor

# twigキャッシュ用ディレクトリ作成
mkdir ${DIR}/app/cache/
chown -R daemon:wheel ${DIR}/app/cache/
chmod -R 775 ${DIR}/app/cache/

# テーブル作成SQL
SQL_TEST=$DIR/ddl/test.sql

# MySQLをバッチモードで実行するコマンド
CMD_MYSQL="mysql -u miniblog -pminimini"

# SQLを読み込んで実行
$CMD_MYSQL < $SQL_TEST

