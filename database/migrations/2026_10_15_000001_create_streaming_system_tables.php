<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('type')->default('both'); // movie, music, both
            $table->text('description')->nullable();
            $table->string('icon')->nullable();
            $table->timestamps();
        });

        Schema::create('movies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('categories')->cascadeOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('tagline')->nullable();
            $table->text('synopsis');
            $table->string('director');
            $table->string('cast')->nullable();
            $table->integer('release_year')->default(2025);
            $table->integer('duration_minutes')->default(90);
            $table->string('access_type')->default('free'); // free, vip, ticket
            $table->decimal('ticket_price', 10, 2)->default(0);
            $table->string('video_url');
            $table->string('trailer_url')->nullable();
            $table->string('poster_url');
            $table->string('banner_url');
            $table->string('location_tag')->nullable();
            $table->integer('views_count')->default(0);
            $table->decimal('rating', 3, 1)->default(4.8);
            $table->boolean('featured')->default(false);
            $table->timestamps();
        });

        Schema::create('tracks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->nullable()->constrained('categories')->nullOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('artist');
            $table->string('album')->nullable();
            $table->integer('release_year')->default(2025);
            $table->integer('duration_seconds')->default(210);
            $table->string('access_type')->default('free'); // free, vip
            $table->string('audio_url');
            $table->string('cover_url');
            $table->string('genre')->nullable();
            $table->text('lyrics')->nullable();
            $table->text('description')->nullable();
            $table->integer('plays_count')->default(0);
            $table->boolean('featured')->default(false);
            $table->timestamps();
        });

        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('ticket_code')->unique();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('movie_id')->constrained('movies')->cascadeOnDelete();
            $table->decimal('price_paid', 10, 2);
            $table->string('payment_method')->default('dompet_bahari');
            $table->string('status')->default('active'); // active, expired
            $table->timestamp('valid_until');
            $table->timestamps();
        });

        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('plan_code');
            $table->string('plan_name');
            $table->decimal('price_paid', 10, 2);
            $table->string('payment_method')->default('dompet_bahari');
            $table->string('status')->default('active');
            $table->timestamp('starts_at');
            $table->timestamp('expires_at');
            $table->timestamps();
        });

        Schema::create('playlists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('cover_url')->nullable();
            $table->boolean('is_public')->default(true);
            $table->timestamps();
        });

        Schema::create('playlist_tracks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('playlist_id')->constrained('playlists')->cascadeOnDelete();
            $table->foreignId('track_id')->constrained('tracks')->cascadeOnDelete();
            $table->integer('order')->default(0);
            $table->timestamps();
        });

        Schema::create('favorites', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('favoriteable_type');
            $table->unsignedBigInteger('favoriteable_id');
            $table->timestamps();
        });

        Schema::create('wallet_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('type'); // topup, purchase_ticket, purchase_subscription
            $table->decimal('amount', 12, 2);
            $table->string('reference_id')->nullable();
            $table->string('description');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wallet_transactions');
        Schema::dropIfExists('favorites');
        Schema::dropIfExists('playlist_tracks');
        Schema::dropIfExists('playlists');
        Schema::dropIfExists('subscriptions');
        Schema::dropIfExists('tickets');
        Schema::dropIfExists('tracks');
        Schema::dropIfExists('movies');
        Schema::dropIfExists('categories');
    }
};
