<?php
/**
 * MineAdmin is committed to providing solutions for quickly building web applications
 * Please view the LICENSE file that was distributed with this source code,
 * For the full copyright and license information.
 * Thank you very much for using MineAdmin.
 *
 * @Author X.Mo<root@imoi.cn>
 * @Link   https://gitee.com/xmo/MineAdmin
 */

use Hyperf\Database\Schema\Schema;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Migrations\Migration;

class CreateSystemApiLogTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('system_api_log', function (Blueprint $table) {
            $table->engine = 'Innodb';
            $table->comment('接口日志表');
            $table->bigIncrements('id')->comment('主键');
            $table->addColumn('bigInteger', 'api_id', ['unsigned' => true, 'comment' => 'api ID']);
            $table->addColumn('string', 'api_name', ['length' => 32, 'comment' => '接口名称']);
            $table->addColumn('string', 'access_name', ['length' => 64, 'comment' => '接口访问名称']);
            $table->addColumn('text', 'request_data', ['comment' => '请求数据'])->nullable();
            $table->addColumn('string', 'response_code', ['length' => 5, 'comment' => '响应状态码'])->nullable();
            $table->addColumn('text', 'response_data', ['comment' => '响应数据'])->nullable();
            $table->addColumn('ipAddress', 'ip', ['comment' => '访问IP地址'])->nullable();
            $table->addColumn('string', 'ip_location', ['length' => 255, 'comment' => 'IP所属地'])->nullable();
            $table->addColumn('timestamp', 'access_time', ['precision' => 0, 'comment' => '访问时间'])->nullable();
            $table->addColumn('string', 'remark', ['length' => 255, 'comment' => '备注'])->nullable();
            $table->index(['api_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('system_api_log');
    }
}
