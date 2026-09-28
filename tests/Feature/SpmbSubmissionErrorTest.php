<?php

namespace Tests\Feature;

use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Tests\TestCase;

class SpmbSubmissionErrorTest extends TestCase
{
    public function test_missing_database_column_returns_actionable_error_without_sql_or_personal_data(): void
    {
        config(['app.debug' => true, 'logging.default' => 'null']);
        $previous = new \PDOException('Unknown column; private applicant data');
        $previous->errorInfo = ['42S22', 1054, 'Unknown column'];
        $exception = new QueryException('mysql', 'insert into private_table values (?)', ['private applicant data'], $previous);
        $request = Request::create('/spmb', 'POST', [], [], [], ['HTTP_ACCEPT' => 'application/json']);
        $response = app(ExceptionHandler::class)->render($request, $exception);

        $this->assertSame(500, $response->getStatusCode());
        $data = json_decode($response->getContent(), true);
        $this->assertSame('SPMB_DB_SCHEMA', $data['error_code']);
        $this->assertStringContainsString('pembaruan database', $data['message']);
        $this->assertStringStartsWith('SPMB-', $data['reference']);
        $this->assertStringNotContainsString('private', $response->getContent());
        $this->assertArrayNotHasKey('trace', $data);
    }

    public function test_failed_file_storage_returns_specific_error(): void
    {
        config(['logging.default' => 'null']);
        $request = Request::create('/spmb', 'POST', [], [], [], ['HTTP_ACCEPT' => 'application/json']);
        $response = app(ExceptionHandler::class)->render($request,
            new \Symfony\Component\HttpKernel\Exception\HttpException(500, 'Bukti transfer gagal disimpan. Silakan coba lagi.'));
        $this->assertSame('SPMB_UPLOAD_STORAGE', json_decode($response->getContent(), true)['error_code']);
    }

    public function test_validation_errors_keep_field_messages_and_422_status(): void
    {
        $request = Request::create('/spmb', 'POST', [], [], [], ['HTTP_ACCEPT' => 'application/json']);
        $response = app(ExceptionHandler::class)->render($request,
            \Illuminate\Validation\ValidationException::withMessages(['bukti' => 'Mohon upload bukti transfer.']));
        $this->assertSame(422, $response->getStatusCode());
        $this->assertSame(['Mohon upload bukti transfer.'], json_decode($response->getContent(), true)['errors']['bukti']);
    }
}
