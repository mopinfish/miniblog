# /bin/zsh

# このスクリプト(db_init.sh)のディレクトリの絶対パスを取得
DIR=$(cd $(dirname $0); pwd)

# テーブル作成SQL
SQL_TEST=$DIR/ddl/test.sql

# MySQLをバッチモードで実行するコマンド
CMD_MYSQL="mysql -u otsukano -posukano "

# SQLを読み込んで実行
mysql -h localhost -u miniblog -pminimini miniblog < $SQL_TEST

