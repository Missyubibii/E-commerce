@extends('layouts.app')

@section('title', 'Reviews for ' . $product->name)

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Reviews Summary -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-2xl font-bold mb-4">Customer Reviews</h2>
                <div class="flex items-center mb-6">
                    <div class="text-4xl font-bold text-gray-900">{{ number_format($averageRating, 1) }}</div>
                    <div class="ml-4">
                        <div class="flex items-center">
                            @for ($i = 1; $i <= 5; $i++)
                                <svg class="w-5 h-5 {{ $i <= round($averageRating) ? 'text-yellow-400' : 'text-gray-300' }}"
                                    fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                            @endfor
                        </div>
                        <p class="text-sm text-gray-500 mt-1">Based on {{ $reviews->total() }} reviews</p>
                    </div>
                </div>

                <!-- Rating Distribution -->
                <div class="space-y-2">
                    @for ($i = 5; $i >= 1; $i--)
                        <div class="flex items-center">
                            <div class="w-12 text-sm text-gray-600">{{ $i }} star</div>
                            <div class="flex-1 h-4 mx-2 bg-gray-200 rounded">
                                @php
                                    $percentage = $reviews->total() > 0
                                        ? ($ratingDistribution[$i] / $reviews->total()) * 100
                                        : 0;
                                @endphp
                                <div class="h-4 bg-yellow-400 rounded" style="width: {{ $percentage }}%"></div>
                            </div>
                            <div class="w-12 text-sm text-gray-600">{{ $ratingDistribution[$i] }}</div>
                        </div>
                    @endfor
                </div>

                @if(auth()->check() && !$hasPurchased)
                    <div class="mt-6">
                        <button type="button" onclick="showReviewForm()"
                            class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors duration-150">
                            Write a Review
                        </button>
                    </div>
                @endif
            </div>
        </div>

        <!-- Reviews List -->
        <div class="lg:col-span-2">
            <!-- Review Form -->
            @if(auth()->check() && !$hasPurchased)
                <div id="reviewForm" class="hidden bg-white rounded-lg shadow-md p-6 mb-6">
                    <h3 class="text-lg font-semibold mb-4">Write Your Review</h3>
                    <form action="{{ route('reviews.store', $product) }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Rating</label>
                            <div class="flex items-center space-x-2">
                                @for ($i = 1; $i <= 5; $i++)
                                    <label class="cursor-pointer">
                                        <input type="radio" name="rating" value="{{ $i }}" class="hidden peer">
                                        <svg class="w-8 h-8 text-gray-300 peer-checked:text-yellow-400"
                                            fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                    </label>
                                @endfor
                            </div>
                            @error('rating')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="comment" class="block text-sm font-medium text-gray-700">Review</label>
                            <textarea name="comment" id="comment" rows="4"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                placeholder="Share your thoughts about this product..."></textarea>
                            @error('comment')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex justify-end">
                            <button type="submit"
                                class="bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors duration-150">
                                Submit Review
                            </button>
                        </div>
                    </form>
                </div>
            @endif

            <!-- Reviews -->
            <div class="space-y-6">
                @forelse($reviews as $review)
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <div class="flex items-center">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <svg class="w-5 h-5 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}"
                                            fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                    @endfor
                                </div>
                                <p class="mt-1 text-sm text-gray-600">By {{ $review->user->name }}</p>
                                @if($review->verified_purchase)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Verified Purchase
                                    </span>
                                @endif
                            </div>
                            <span class="text-sm text-gray-500">{{ $review->created_at->diffForHumans() }}</span>
                        </div>
                        <p class="text-gray-700">{{ $review->comment }}</p>

                        @if(auth()->id() === $review->user_id)
                            <div class="mt-4 flex space-x-4">
                                <button onclick="showEditForm({{ $review->id }})"
                                    class="text-sm text-blue-600 hover:text-blue-700">Edit</button>
                                <form action="{{ route('reviews.destroy', $review) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-sm text-red-600 hover:text-red-700">Delete</button>
                                </form>
                            </div>
                        @endif
                    </div>
                @empty
                    <div class="bg-white rounded-lg shadow-md p-6 text-center">
                        <p class="text-gray-500">No reviews yet. Be the first to review this product!</p>
                    </div>
                @endforelse

                <!-- Pagination -->
                <div class="mt-6">
                    {{ $reviews->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function showReviewForm() {
    document.getElementById('reviewForm').classList.remove('hidden');
}

function showEditForm(reviewId) {
    // Implement edit form logic
}
</script>
@endpush
@endsection
