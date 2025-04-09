public function up()
{
    Schema::table('votes', function (Blueprint $table) {
        $table->string('vote_type')->nullable()->after('user_id');
    });
}

public function down()
{
    Schema::table('votes', function (Blueprint $table) {
        $table->dropColumn('vote_type');
    });
}
