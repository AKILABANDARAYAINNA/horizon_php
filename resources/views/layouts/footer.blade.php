<!-- resources/views/layouts/footer.blade.php -->
<footer class="bg-green-600 text-white shadow py-6">
    <div class="container mx-auto px-6 text-center">
        <p>&copy; {{ date('Y') }} Horizon Real Estates. All rights reserved.</p>

        <!-- Footer Links -->
        <div class="mt-4 space-x-4">
            <a href="{{ route('privacy') }}" class="hover:underline">Privacy Policy</a>            
            <a href="{{ route('terms') }}" class="hover:underline">Terms of Service</a>            
            <a href="{{ route('contact') }}" class="hover:underline">Contact Us</a>
        </div>

        <!-- Social Media Icons -->
        <div class="mt-6 flex justify-center space-x-6">
            <a href="https://www.facebook.com/" target="_blank" class="hover:text-yellow-300">
                <img src="{{ asset('assets/icons/fb_logo.png') }}" alt="Facebook" class="w-6 h-6 inline-block">
            </a>
            <a href="https://www.instagram.com/" target="_blank" class="hover:text-yellow-300">
                <img src="{{ asset('assets/icons/ig_logo.png') }}" alt="Instagram" class="w-6 h-6 inline-block">
            </a>
            <a href="https://www.youtube.com/" target="_blank" class="hover:text-yellow-300">
                <img src="{{ asset('assets/icons/yt_logo.png') }}" alt="YouTube" class="w-8 h-8 inline-block">
            </a>
        </div>
    </div>
</footer>
