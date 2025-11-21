<!-- Community Testimonials / Success Stories - بخش صدای جامعه جهانی ما -->
<section id="testimonials" class="py-16 md:py-24 bg-pure-white fade-in-section">
    <div class="container mx-auto px-6 text-center">
        <h2 class="text-3xl md:text-5xl font-extrabold font-vazirmatn text-gentle-black mb-6">
            {{ __('langWelcome.testimonials_title') }}
        </h2>
        <div class="section-separator"></div>
        <p class="text-lg md:text-xl text-gray-700 mb-12 max-w-4xl mx-auto font-vazirmatn">
            {{ __('langWelcome.testimonials_subtitle') }}
        </p>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="testimonial-card p-8 flex flex-col items-center text-center">
                <img src="https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?ixlib=rb-4.0.3&auto=format&fit=crop&w=250&q=80"
                      alt="{{ __('langWelcome.testimonials_card1_alt') }}"
                      class="w-24 h-24 rounded-full mb-6 object-cover border-4 border-earth-green shadow-md">
                <div class="text-xl text-gray-800 italic mb-5 font-vazirmatn relative z-10 before:content-['"'] before:absolute before:-top-4 before:-right-4 before:text-5xl before:text-gray-200 after:content-['"'] after:absolute after:-bottom-4 after:-left-4 after:text-5xl after:text-gray-200">
                    {{ __('langWelcome.testimonials_card1_quote') }}
                </div>
                <div class="mt-auto pt-4">
                    <p class="font-bold text-xl font-vazirmatn text-gentle-black">{{ __('langWelcome.testimonials_card1_name') }}</p>
                    <p class="text-sm font-vazirmatn text-gray-600">{{ __('langWelcome.testimonials_card1_role') }}</p>
                </div>
            </div>

            <div class="testimonial-card p-8 flex flex-col items-center text-center">
                <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?ixlib=rb-4.0.3&auto=format&fit=crop&w=250&q=80"
                      alt="{{ __('langWelcome.testimonials_card2_alt') }}"
                      class="w-24 h-24 rounded-full mb-6 object-cover border-4 border-ocean-blue shadow-md">
                <div class="text-xl text-gray-800 italic mb-5 font-vazirmatn relative z-10 before:content-['"'] before:absolute before:-top-4 before:-right-4 before:text-5xl before:text-gray-200 after:content-['"'] after:absolute after:-bottom-4 after:-left-4 after:text-5xl after:text-gray-200">
                    {{ __('langWelcome.testimonials_card2_quote') }}
                </div>
                <div class="mt-auto pt-4">
                    <p class="font-bold text-xl font-vazirmatn text-gentle-black">{{ __('langWelcome.testimonials_card2_name') }}</p>
                    <p class="text-sm font-vazirmatn text-gray-600">{{ __('langWelcome.testimonials_card2_role') }}</p>
                </div>
            </div>

            <div class="testimonial-card p-8 flex flex-col items-center text-center">
                <img src="https://images.unsplash.com/photo-1438761681033-6461ffad8d80?ixlib=rb-4.0.3&auto=format&fit=crop&w=250&q=80"
                      alt="{{ __('langWelcome.testimonials_card3_alt') }}"
                      class="w-24 h-24 rounded-full mb-6 object-cover border-4 border-digital-gold shadow-md">
                <div class="text-xl text-gray-800 italic mb-5 font-vazirmatn relative z-10 before:content-['"'] before:absolute before:-top-4 before:-right-4 before:text-5xl before:text-gray-200 after:content-['"'] after:absolute after:-bottom-4 after:-left-4 after:text-5xl after:text-gray-200">
                    {{ __('langWelcome.testimonials_card3_quote') }}
                </div>
                <div class="mt-auto pt-4">
                    <p class="font-bold text-xl font-vazirmatn text-gentle-black">{{ __('langWelcome.testimonials_card3_name') }}</p>
                    <p class="text-sm font-vazirmatn text-gray-600">{{ __('langWelcome.testimonials_card3_role') }}</p>
                </div>
            </div>
        </div>
    </div>
</section>
