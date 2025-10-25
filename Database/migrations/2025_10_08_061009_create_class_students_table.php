 <?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    return new class extends Migration
    {
        public function up(): void
        {
            Schema::create('class_students', function (Blueprint $table) {
                $table->engine = 'InnoDB';

                $table->uuid('id')->primary();

                // classes.primary = string('nisn', 20)
                $table->string('class_id', 20);

                // users.pk = uuid (sesuaikan kalau users pakai id numeric)
                $table->uuid('student_id');

                $table->timestamp('enrolled_at')->nullable();
                $table->enum('status', ['enrolled', 'dropped', 'completed'])
                    ->default('enrolled')
                    ->comment('Status keikutsertaan siswa di kelas');

                $table->timestamps();
                $table->softDeletes();

                $table->unique(['class_id', 'student_id'], 'class_student_unique');

                // foreign keys
                $table->foreign('class_id')
                    ->references('nisn')->on('classes')
                    ->cascadeOnDelete();

                $table->foreign('student_id')
                    ->references('uuid')->on('users')
                    ->cascadeOnDelete();
            });
        }

        public function down(): void
        {
            Schema::dropIfExists('class_students');
        }
    };
