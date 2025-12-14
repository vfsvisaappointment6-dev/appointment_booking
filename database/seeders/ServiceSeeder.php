<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Service;
use Illuminate\Support\Str;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $services = [
            // Hair Services
            [
                'name' => 'Hair Cutting & Styling',
                'description' => 'Professional hair cutting and styling service. Includes shampoo, conditioning, and styling with modern techniques. Perfect for all hair types and styles.',
                'duration' => 60,
                'price' => 50.00,
                'status' => 'active',
                'image_url' => 'https://djn2oq6v2lacp.cloudfront.net/wp-content/uploads/2025/04/hair-style-961.jpg',
            ],
            [
                'name' => 'Hair Coloring & Highlights',
                'description' => 'Professional hair coloring service using premium quality dyes. Includes color consultation, application, and deep conditioning treatment.',
                'duration' => 120,
                'price' => 120.00,
                'status' => 'active',
                'image_url' => 'https://kao-h.assetsadobe3.com/is/image/content/dam/sites/kaousa/www-johnfrieda-com/uk/en/blog-images/Woman%20with%20pretty%20blonde%20highlights.jpg?fmt=png-alpha&wid=1707',
            ],
            [
                'name' => 'Braiding & Weaving',
                'description' => 'Expert braiding services including cornrows, box braids, kilo braids, twists, and more. Includes hair care consultation and maintenance tips.',
                'duration' => 180,
                'price' => 80.00,
                'status' => 'active',
                'image_url' => 'https://i.pinimg.com/736x/c2/92/c3/c292c3982227506b4e63201fa121662b.jpg',
            ],
            [
                'name' => 'Relaxation & Straightening',
                'description' => 'Professional hair relaxation and straightening service using quality products. Includes scalp treatment, deep conditioning, and styling.',
                'duration' => 90,
                'price' => 70.00,
                'status' => 'active',
                'image_url' => 'https://topclassactions.com/wp-content/uploads/2022/11/shutterstock_1227139510-1024x683.jpg.webp',
            ],
            [
                'name' => 'Hair Wash & Treatment',
                'description' => 'Deep cleansing hair wash with premium shampoo, conditioning treatment, and scalp massage. Ideal for hair maintenance and recovery.',
                'duration' => 45,
                'price' => 35.00,
                'status' => 'active',
                'image_url' => 'https://media.istockphoto.com/id/1321544366/photo/hair-stylist-in-brazil.jpg?s=612x612&w=0&k=20&c=HV_OIWl-t5-KSGXFajjIFfqUE9Tq2a33tAb996qNKrY=',
            ],
            [
                'name' => 'Wig Installation & Styling',
                'description' => 'Professional wig fitting, installation, and styling service. Includes proper wig care education and maintenance tips.',
                'duration' => 60,
                'price' => 45.00,
                'status' => 'active',
                'image_url' => 'https://media.istockphoto.com/id/921879992/photo/the-hairdresser-does-hair-extensions-to-a-young-girl-a-blonde-in-a-beauty-salon.jpg?s=612x612&w=0&k=20&c=z03YVYttRdS6Zhki44fpqBd1ix--Fc3CGG16sEwX6gQ=',
            ],

            // Facial & Skincare Services
            [
                'name' => 'Facial Treatment',
                'description' => 'Customized facial treatment based on skin type. Includes cleansing, exfoliation, extraction, massage, and hydrating mask.',
                'duration' => 60,
                'price' => 65.00,
                'status' => 'active',
                'image_url' => 'https://media.istockphoto.com/id/2153502973/photo/shot-of-a-beautiful-young-woman-enjoying-face-massage-at-the-beauty-salon-facial-massage.jpg?s=612x612&w=0&k=20&c=oEEhZgeYAnC_8yKxoFoVvFdlMPjugxNtXR5BxbzdjQY=',
            ],
            [
                'name' => 'Acne Treatment',
                'description' => 'Specialized acne treatment service designed to target breakouts and prevent future occurrences. Includes professional extraction and healing mask.',
                'duration' => 45,
                'price' => 55.00,
                'status' => 'active',
                'image_url' => 'https://media.istockphoto.com/id/1307623206/photo/face-pimples-skin-cleaning-at-dermatologist-close-up.jpg?s=612x612&w=0&k=20&c=ry6B7H9ejy8D068LoduFDG9KyKGJrR8UxlcS3-ZOc90=',
            ],
            [
                'name' => 'Skin Brightening Treatment',
                'description' => 'Professional skin brightening and lightening service using safe and effective products. Perfect for uneven skin tone correction.',
                'duration' => 60,
                'price' => 75.00,
                'status' => 'active',
                'image_url' => 'https://media.istockphoto.com/id/2217395822/photo/woman-applying-gold-face-mask-at-home-for-skincare-routine-while-wearing-a-towel-on-her-head.jpg?s=612x612&w=0&k=20&c=bnPJ3kUjiW5UpW9ziCrVPassY5JmtzUwQpOngQYbbTw=',
            ],

            // Body Treatment Services
            [
                'name' => 'Body Massage & Relaxation',
                'description' => 'Professional full-body massage service for relaxation and stress relief. Includes aromatherapy and muscle tension relief.',
                'duration' => 60,
                'price' => 60.00,
                'status' => 'active',
                'image_url' => 'https://media.istockphoto.com/id/502157975/photo/she-had-those-healing-hands.jpg?s=612x612&w=0&k=20&c=7bI-YgdRkNs2jJjKUWd8tTnJUgwpQnVwfHaBc8F-yQE=',
            ],
            [
                'name' => 'Body Scrub & Exfoliation',
                'description' => 'Luxurious body scrub treatment to remove dead skin and improve skin texture. Followed by moisturizing lotion application.',
                'duration' => 45,
                'price' => 40.00,
                'status' => 'active',
                'image_url' => 'https://media.istockphoto.com/id/1372666306/photo/shot-of-an-attractive-young-woman-getting-an-exfoliating-massage-at-a-spa.jpg?s=612x612&w=0&k=20&c=OwWrucdLLCACTgAHook8mnpcc8SJyoTLfNuUotKBsec=',
            ],
            [
                'name' => 'Body Spa Treatment',
                'description' => 'Complete body spa package including exfoliation, massage, and hydrating treatment. Ultimate relaxation experience.',
                'duration' => 90,
                'price' => 85.00,
                'status' => 'active',
                'image_url' => 'https://media.istockphoto.com/id/2196778643/photo/relaxing-facial-for-men-at-a-skincare-clinic.jpg?s=612x612&w=0&k=20&c=InPvLJQdu5fMztBB0oPUj_e-qlWlMBV0e9erNkP_wSQ=',
            ],

            // Nail Services
            [
                'name' => 'Manicure Service',
                'description' => 'Professional manicure including nail shaping, cuticle care, polish application, and hand massage. Choose from various colors and designs.',
                'duration' => 45,
                'price' => 30.00,
                'status' => 'active',
                'image_url' => 'https://media.istockphoto.com/id/2184380235/photo/women-having-manicure-at-beauty-spa.jpg?s=612x612&w=0&k=20&c=6dRJLIYISI7FnaFC9qQpURR1NAYWqkUIpZuvC6LnzjY=',
            ],
            [
                'name' => 'Pedicure Service',
                'description' => 'Complete pedicure service including foot soak, exfoliation, nail care, polish application, and foot massage.',
                'duration' => 60,
                'price' => 40.00,
                'status' => 'active',
                'image_url' => 'https://media.istockphoto.com/id/2027647456/photo/close-up-of-busy-beautician-working-in-a-beauty-salon.jpg?s=612x612&w=0&k=20&c=qjTGq3nVymXK4KCsAeDK3iQE91275tiVFKBfqZhHFV8=',
            ],
            [
                'name' => 'Gel Manicure',
                'description' => 'Long-lasting gel manicure service that lasts 3-4 weeks. Includes design customization and professional application.',
                'duration' => 45,
                'price' => 50.00,
                'status' => 'active',
                'image_url' => 'https://media.istockphoto.com/id/1250022422/photo/woman-receiving-french-manicure-by-beautician.jpg?s=612x612&w=0&k=20&c=33XzHUKzLTK2xqSpk9yT0s9c-geujO1tj8Eb9ULb9fA=',
            ],
            [
                'name' => 'Gel Pedicure',
                'description' => 'Professional gel pedicure service for long-lasting color and shine. Perfect for special occasions or everyday wear.',
                'duration' => 60,
                'price' => 60.00,
                'status' => 'active',
                'image_url' => 'https://media.istockphoto.com/id/1280301022/photo/manicure-master-is-painting-female-toenails.jpg?s=612x612&w=0&k=20&c=bR_RKlEnpdjvZCezzBeVQ3Qpr-XnZ4D22mROvs-duXI=',
            ],
            [
                'name' => 'Nail Art Design',
                'description' => 'Custom nail art design service including painting, decorations, and embellishments. Express your style with unique designs.',
                'duration' => 60,
                'price' => 45.00,
                'status' => 'active',
                'image_url' => 'https://media.istockphoto.com/id/1399230916/photo/abstract-manicure-with-multicolored-lines.jpg?s=612x612&w=0&k=20&c=3cW1bguK36ouLFvRTb3EsvGiIghV1DZ5HrJom-er48A=',
            ],

            // Eyebrow & Eyelash Services
            [
                'name' => 'Eyebrow Threading',
                'description' => 'Precise eyebrow shaping using traditional threading method. Clean, defined, and perfectly shaped eyebrows.',
                'duration' => 20,
                'price' => 15.00,
                'status' => 'active',
                'image_url' => 'https://media.istockphoto.com/id/177177852/photo/woman-on-facial-hair-removal-threading-procedure.jpg?s=612x612&w=0&k=20&c=H_Ojngs3D6SKquUtRQVBh2jc2oIhtQSWfiteQIujFn4=',
            ],
            [
                'name' => 'Eyebrow Tinting',
                'description' => 'Professional eyebrow tinting to enhance shape and color. Perfect for lighter eyebrows or special occasions.',
                'duration' => 30,
                'price' => 20.00,
                'status' => 'active',
                'image_url' => 'https://media.istockphoto.com/id/1285923568/photo/eyebrow-makeup-treatment.jpg?s=612x612&w=0&k=20&c=9YlAwyG3vUnv0LEWsgUwwN7D5FxHtaMHx8xzuqJQUEc=',
            ],
            [
                'name' => 'Eyelash Extension',
                'description' => 'Professional eyelash extension service for fuller, longer-looking lashes. Natural appearance with long-lasting results.',
                'duration' => 90,
                'price' => 90.00,
                'status' => 'active',
                'image_url' => 'https://media.istockphoto.com/id/845708412/photo/eyelash-extension-procedure-woman-eye-with-long-eyelashes-lashes-close-up-macro-selective-focus.jpg?s=612x612&w=0&k=20&c=GRDESfELhTdQCItPCrs_sDbWs9sxxmilFhmJnK0FoIA=',
            ],
            [
                'name' => 'Lash Lift & Tint',
                'description' => 'Non-invasive lash lift service that curls lashes and adds tint for a dramatic look without extensions.',
                'duration' => 60,
                'price' => 70.00,
                'status' => 'active',
                'image_url' => 'https://www.jeevibrowstudio.com.au/wp-content/uploads/2023/02/Eyelash-Lifts-Jeevi-brow-Studio.-1038x576.png',
            ],

            // Make-up Services
            [
                'name' => 'Makeup Application',
                'description' => 'Professional makeup application for events, parties, or daily wear. Customized to your preferences and skin tone.',
                'duration' => 60,
                'price' => 55.00,
                'status' => 'active',
                'image_url' => 'https://media.istockphoto.com/id/1206721088/photo/skin-care-business.jpg?s=612x612&w=0&k=20&c=PssZwSwFu4ZTe9UU5izKcJLDbMbZI68l3WNEsyCquL4=',
            ],
            [
                'name' => 'Bridal Makeup Package',
                'description' => 'Complete bridal makeup service including consultation, trial, and on-the-day application. Ensures you look perfect for your big day.',
                'duration' => 90,
                'price' => 150.00,
                'status' => 'active',
                'image_url' => 'https://images.squarespace-cdn.com/content/v1/5a9ee2ec70e8023a569f383b/349d7d82-0fb0-4236-9f1f-64cb22fe644c/simplebeautyartistry-bridal-makeup-artist-blogbanner1.jpg?format=2500w',
            ],
            [
                'name' => 'Party & Event Makeup',
                'description' => 'Glamorous makeup application for parties, weddings, and special events. Professional and long-lasting makeup.',
                'duration' => 60,
                'price' => 65.00,
                'status' => 'active',
                'image_url' => 'https://images.squarespace-cdn.com/content/v1/62879968c4e7ab7d3ddc2b33/6d9fca81-7503-485b-9784-6fff84db640a/womens+festival+makeup+ideas.jpg?format=1500w',
            ],

            // Waxing Services
            [
                'name' => 'Body Waxing - Full Legs',
                'description' => 'Professional full leg waxing service for smooth, hairless legs. Long-lasting results lasting 3-4 weeks.',
                'duration' => 45,
                'price' => 50.00,
                'status' => 'active',
                'image_url' => 'https://bodymattersware.co.uk/wp-content/uploads/2014/04/wax1small-300x200.jpg',
            ],
            [
                'name' => 'Body Waxing - Arms',
                'description' => 'Professional arm waxing service for smooth underarms and arms. Quick and efficient with minimal discomfort.',
                'duration' => 20,
                'price' => 20.00,
                'status' => 'active',
                'image_url' => 'https://media.istockphoto.com/id/1043783456/photo/beautician-removes-hair-from-a-womans-hand.jpg?s=612x612&w=0&k=20&c=LjcAU3VdWo7_8kRKjhbeBtV8NRFTK0ceilQ5NZiAHeo=',
            ],
            [
                'name' => 'Bikini Waxing',
                'description' => 'Professional bikini area waxing service. Available in different styles from basic to Brazilian.',
                'duration' => 30,
                'price' => 35.00,
                'status' => 'active',
                'image_url' => 'https://media.istockphoto.com/id/1220348344/photo/waiting-for-wax-to-be-ready-for-peeling-off-from-clients-skin-stock-photo.jpg?s=612x612&w=0&k=20&c=gw7OG0HWIi6WLMK5t9nrkgrk1lB8Hci1gVGIgdLatNc=',
            ],
            [
                'name' => 'Full Body Waxing',
                'description' => 'Complete body waxing service covering all areas. Professional and hygienic with high-quality waxing products.',
                'duration' => 90,
                'price' => 100.00,
                'status' => 'active',
                'image_url' => 'https://kelseymarieartistry.com/wp-content/uploads/2024/04/full-body-waxing-to-remove-the-hair-jpg.webp',
            ],

            // Specialized Services
            [
                'name' => 'Hair Loss Treatment Consultation',
                'description' => 'Professional consultation and treatment options for hair loss. Includes scalp analysis and personalized treatment plan.',
                'duration' => 60,
                'price' => 45.00,
                'status' => 'active',
                'image_url' => 'https://media.istockphoto.com/id/1488632661/photo/doctor-exam-scalp-with-microscope.jpg?s=612x612&w=0&k=20&c=0Dy2Kf7nIQHyTMBrmigq_fYPIIoXRxRVbpQem0F-pUE=',
            ],
            [
                'name' => 'Dandruff Treatment',
                'description' => 'Specialized treatment to eliminate dandruff and scalp issues. Includes deep cleansing and medicated treatment.',
                'duration' => 45,
                'price' => 40.00,
                'status' => 'active',
                'image_url' => 'https://media.istockphoto.com/id/1270415974/photo/closeup-hair-with-dandruff-scalp-seborrheic-dermatitis.jpg?s=612x612&w=0&k=20&c=ieWGEh4eezYRPHeQrQg8oYWO3uOwXEeWQtj7o_yj6sA=',
            ],
            [
                'name' => 'Threading Service',
                'description' => 'Professional threading for facial hair removal including upper lip, chin, and cheeks. Precise and long-lasting results.',
                'duration' => 30,
                'price' => 20.00,
                'status' => 'active',
                'image_url' => 'https://media.istockphoto.com/id/1201301861/photo/shot-of-barber-reshaping-young-mans-eyebrows-in-barber-shop.jpg?s=612x612&w=0&k=20&c=RJomYt78DnOs5GbtcYtqJMtMrZJxp70eC0xaOxmSiYo=',
            ],
        ];

        foreach ($services as $service) {
            Service::create([
                'service_id' => Str::uuid(),
                ...$service,
            ]);
        }
    }
}
