<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Recommendation Movie') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Header Section -->
            <div class="mb-8 px-4 sm:px-0">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">New Releases</h1>
                        <p class="text-gray-600 mt-1">Discover the latest movies</p>
                    </div>
                    <div class="flex space-x-2">
                        <button
                            class="filter-btn px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z">
                                </path>
                            </svg>
                            Filter
                        </button>
                        <button
                            class="sort-btn px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 4h13M3 8h9m-9 4h9m5-4v12m0 0l-4-4m4 4l4-4"></path>
                            </svg>
                            Sort
                        </button>
                    </div>
                </div>
            </div>

            <!-- Movies Grid -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-4 xl:grid-cols-4 gap-6"
                        id="movies-grid">
                        @foreach ($films as $movie)
                            <div class="bg-white rounded-lg overflow-hidden shadow-sm hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1 group border border-gray-100 movie-card cursor-pointer"
                                onclick="openModal({{ $movie['id'] }})">
                                <!-- Poster -->
                                <div class="relative overflow-hidden">
                                    <img src="{{ $movie['poster'] }}"
                                        class="w-full aspect-[3/4] object-cover group-hover:scale-105 transition duration-500"
                                        alt="{{ $movie['title'] }}" loading="lazy">
                                    <div
                                        class="absolute top-3 right-3 bg-black bg-opacity-70 text-white text-xs font-semibold px-2 py-1 rounded flex items-center">
                                        <svg class="w-3 h-3 mr-1 text-yellow-400" fill="currentColor"
                                            viewBox="0 0 20 20">
                                            <path
                                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                                            </path>
                                        </svg>
                                        {{ $movie['rating'] }}
                                    </div>
                                </div>

                                <!-- Info -->
                                <div class="p-4">
                                    <h3 class="font-semibold text-gray-800 line-clamp-1 mb-1">{{ $movie['title'] }}</h3>

                                    <div class="flex justify-between text-xs text-gray-500 mb-2">
                                        <span>{{ $movie['year'] }}</span>
                                        <span
                                            class="bg-gray-100 px-2 py-1 rounded text-gray-600">{{ $movie['genre'] }}</span>
                                    </div>

                                    <p class="text-xs text-gray-600 line-clamp-2 mb-3">
                                        {{ Str::limit($movie['synopsis'], 80) }}</p>

                                    <button
                                        class="w-full bg-blue-600 text-white text-xs font-medium py-2 rounded-lg hover:bg-blue-700 transition flex items-center justify-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                        </svg>
                                        Book Now
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Loading State -->
                    <div id="loading" class="hidden flex justify-center mt-12">
                        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-gray-800"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Movie Detail Modal -->
    <div id="movieModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50 hidden">
        <div class="bg-white rounded-xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">
            <div class="p-6">
                <!-- Modal Header -->
                <div class="flex justify-between items-start mb-6">
                    <h2 class="text-3xl font-bold text-gray-900" id="modalTitle">Movie Title</h2>
                    <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600 transition">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <!-- Main Content -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
                    <!-- Poster Section -->
                    <div class="lg:col-span-1">
                        <img id="modalPoster" src="" alt="Movie Poster"
                            class="w-full rounded-xl shadow-lg aspect-[2/3] object-cover">
                    </div>

                    <!-- Details Section -->
                    <div class="lg:col-span-2">

                        <!-- Movie Info -->
                        <div class="bg-gray-50 rounded-lg p-6 mb-6">
                            <div class="grid grid-cols-2 gap-4 mb-4">
                                <div>
                                    <h3 class="text-sm font-medium text-gray-500 mb-1">Duration</h3>
                                    <p id="modalDuration" class="text-gray-900 font-semibold">-</p>
                                </div>
                                <div>
                                    <h3 class="text-sm font-medium text-gray-500 mb-1">Release Year</h3>
                                    <p id="modalReleaseYear" class="text-gray-900 font-semibold">-</p>
                                </div>
                                <div>
                                    <h3 class="text-sm font-medium text-gray-500 mb-1">Rating</h3>
                                    <p id="modalRating" class="text-gray-900 font-semibold flex items-center">
                                        <svg class="w-4 h-4 text-yellow-400 mr-1" fill="currentColor"
                                            viewBox="0 0 20 20">
                                            <path
                                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                                            </path>
                                        </svg>
                                        <span id="modalRatingValue">-</span>
                                    </p>
                                </div>
                                <div>
                                    <h3 class="text-sm font-medium text-gray-500 mb-1">Age Rating</h3>
                                    <span id="modalAgeRating"
                                        class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">-</span>
                                </div>
                            </div>

                            <div class="mb-4">
                                <h3 class="text-sm font-medium text-gray-500 mb-1">Director</h3>
                                <p id="modalDirector" class="text-gray-900 font-medium">-</p>
                            </div>

                            <div>
                                <h3 class="text-sm font-medium text-gray-500 mb-1">Genres</h3>
                                <p id="modalGenres" class="text-gray-900">-</p>
                            </div>
                        </div>

                        <!-- Synopsis -->
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-3">Synopsis</h3>
                            <p id="modalSynopsis" class="text-gray-700 leading-relaxed">-</p>
                        </div>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="flex justify-end space-x-3 mt-8 pt-6 border-t border-gray-200">
                    <button onclick="closeModal()"
                        class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition font-medium">Close</button>
                    <a id="modalBookNow" href="#"
                        class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z">
                            </path>
                        </svg>
                        Book Now
                    </a>
                </div>

                <!-- More Like This Section -->
                <div class="border-t border-gray-200 pt-6 mt-5">
                    <h3 class="text-xl font-bold text-gray-900 mb-4">More Like This</h3>
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4" id="recommendationsGrid">
                        <!-- Recommended movies will be populated here -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Movie data from server
        const moviesData = @json($films);

        async function openModal(movieId) {
            try {
                // Show loading state
                document.getElementById('movieModal').classList.remove('hidden');
                document.body.style.overflow = 'hidden';

                // Try to get from preloaded data first
                let movie = moviesData.find(m => m.id === movieId);

                if (!movie) {
                    // Fetch fresh data if not found
                    const response = await fetch(`/films/${movieId}`);
                    movie = await response.json();
                }

                // Populate modal with movie data from your controller
                document.getElementById('modalTitle').textContent = movie.title;
                document.getElementById('modalDuration').textContent = movie.duration;
                document.getElementById('modalSynopsis').textContent = movie.synopsis || 'No synopsis available';
                document.getElementById('modalPoster').src = movie.poster || '/placeholder-poster.jpg';
                document.getElementById('modalPoster').alt = `Poster for ${movie.title}`;
                document.getElementById('modalRatingValue').textContent = movie.rating;
                document.getElementById('modalReleaseYear').textContent = movie.year;
                document.getElementById('modalAgeRating').textContent = movie.rating_original;
                document.getElementById('modalDirector').textContent = movie.director;
                document.getElementById('modalGenres').textContent = Array.isArray(movie.genres) ? movie.genres.join(
                    ', ') : movie.genre;

                // Set booking link
                document.getElementById('modalBookNow').href = `/pemesanan/${movieId}`;

                // Populate recommendations (show 4 random movies excluding current one)
                populateRecommendations(movieId);

            } catch (error) {
                console.error('Error loading movie details:', error);
                closeModal();
            }
        }

        function populateRecommendations(currentMovieId) {
            const recommendationsGrid = document.getElementById('recommendationsGrid');
            recommendationsGrid.innerHTML = '';

            // Get 4 random movies excluding the current one
            const otherMovies = moviesData.filter(m => m.id !== currentMovieId);
            const recommendations = otherMovies
                .sort(() => 0.5 - Math.random())
                .slice(0, 4);

            recommendations.forEach(movie => {
                const movieElement = document.createElement('div');
                movieElement.className =
                    'bg-white rounded-lg overflow-hidden shadow-sm hover:shadow-md transition border border-gray-100 cursor-pointer';
                movieElement.onclick = () => openModal(movie.id);

                movieElement.innerHTML = `
                <img src="${movie.poster || '/placeholder-poster.jpg'}" alt="${movie.title}" class="w-full aspect-[2/3] object-cover">
                <div class="p-3">
                    <h4 class="font-semibold text-gray-800 text-sm line-clamp-1 mb-1">${movie.title}</h4>
                    <div class="flex justify-between text-xs text-gray-500">
                        <span>${movie.year}</span>
                        <span class="bg-gray-100 px-2 py-1 rounded">${movie.genre}</span>
                    </div>
                </div>
            `;

                recommendationsGrid.appendChild(movieElement);
            });
        }

        function closeModal() {
            document.getElementById('movieModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        // Close modal when clicking outside
        document.getElementById('movieModal').addEventListener('click', function(e) {
            if (e.target === this) closeModal();
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closeModal();
        });

        // Simple filter and sort functionality
        document.querySelector('.filter-btn')?.addEventListener('click', function() {
            alert('Filter functionality to be implemented');
        });

        document.querySelector('.sort-btn')?.addEventListener('click', function() {
            alert('Sort functionality to be implemented');
        });
    </script>

    <style>
        .line-clamp-1 {
            overflow: hidden;
            display: -webkit-box;
            -webkit-line-clamp: 1;
            -webkit-box-orient: vertical;
        }

        .line-clamp-2 {
            overflow: hidden;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
        }

        #movieModal {
            transition: opacity 0.3s ease;
        }

        #movieModal>div {
            transform: scale(0.95);
            transition: transform 0.3s ease;
        }

        #movieModal:not(.hidden)>div {
            transform: scale(1);
        }

        .movie-card {
            transition: all 0.3s ease;
            cursor: pointer;
        }
    </style>
</x-app-layout>
