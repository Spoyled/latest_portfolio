<!-- Header -->
@include('layouts.header')
        
        <div class="relative w-full" style="max-width: 100%; height: 400px;">
            <!-- Image -->
            <img src="{{ asset('storage/images/A_horizontal_image_related_to_the_IT_industry,_fea.jpg') }}" alt="ProSnap Image" class="w-full h-full object-cover">
    
            <!-- Overlay content -->
            <div class="absolute inset-0 flex flex-col items-center justify-center bg-black bg-opacity-50 text-white p-6">
                <h1 class="text-3xl md:text-4xl font-bold text-yellow-500"> 
            </div>
    
            <!-- Extended Shadow beneath the image -->
            <div class="absolute bottom-0 left-0 right-0 h-32 bg-gradient-to-b from-transparent to-[#f0f4f8]"></div>
        </div>
    
        
    
        
    <!-- Footer -->
    @include('layouts.footer')
    
    