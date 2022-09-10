<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //




        $NURSERY = [
            
            'Bible or Islamic knowledge',
            'Creative Arts',
            'Rhymes or Songs',
            'Computer',
            'Physical exercise',
            'Story telling',        
        ];
    
        $PRIMARY = [
            'English Language',
            'English Primary 1',
            'English Primary 2',
            'English Primary 3',
            'English Primary 4',
            'English Primary 5',
            'English Primary 6',
            'Mathematics',
            'Math Primary 1',
            'Math Primary 2',
            'Math Primary 3',
            'Math Primary 4',
            'Math Primary 5',
            'Math Primary 6',
            'Verbal Reasoning',
            'Spelling',
            'Quantitative Reasoning',
            'Elementary Science',
            'Basic science primary 1',
            'Social Studies',
            'Vocational Aptitude',
            'Health Education',
            'Bible Knowledge',
            'Languages',
            'Creative Arts',
            'Computer',
            'Agric Science',
            'Home Economics',
            'Civic',
            'Music',
            'Moral Instruction',
            'Handwriting',        
        ];
    
    
        $JSS = [
            'Mathematics',
            'English Language',
            'Yoruba or Hausa or Ibo',
            'Basic Science',
            'Social Studies',
            'Fine Arts or Creative Art',
            'Agricultural Science',
            'Civic Education',
            'Physical and Health Education',
            'Business Studies',
            'French',
            'Computer Studies',
            'Home Economics',
            'Music',
            'Basic Technology',
        ];
    
        $WAEC = [
            'Arabic',
            'Agricultural Science',
            'Auto Parts Merchandising',
            'Auto Mechanics',
            'Auto Mechanical Work',
            'Auto Electrical Work',
            'Auto Body Repairs and Spray Painting',
            'Applied Electricity or Basic Electricity',
            'Animal Husbandry',
            'Business Management',
            'Building Construction',
            'Book Keeping',
            'Blocklaying, Bricklaying and Concreting',
            'Biology',
            'Basketry',
            'Crop Husbandry and Horticulture',
            'Cosmetology',
            'Computer Studies',
            'Commerce',
            'Clothing and Textiles',
            'Clerical Office Duties',
            'Civic Education',
            'Christian Religious Studies',
            'Chemistry',
            'Ceramics',
            'Catering Craft Practice',
            'Capentry and Joinery',
            'Dyeing & Bleaching',
            'Data Processing',
            'English Language',
            'Electronics or Basic Electronics',
            'Electrical Installation and Maintenance',
            'Efik',
            'Edo',
            'Economics',
            'Further Mathematics',
            'Furniture Making',
            'French',
            'Forestry',
            'Foods and Nutrition',
            'Fisheries',
            'Financial Accounting',
            'Typewriting',
            'Tourism',
            'Textiles',
            'Technical Drawing',
            'Store Management',
            'Store Keeping',
            'Social Studies',
            'Shorthand',
            'Sculpture',
            'Salesmanship',
            'Refrigeration and Air Conditioning',
            'Radio,television and Electronics Works',
            'Printing Craft Practice',
            'Principles of Cost Accounting',
            'Plumbing and Pipe Fitting',
            'Picture Making',
            'Physics',
            'Physical Education',
            'Photography',
            'Painting and Decorating',
            'Office Practice',
            'Music',
            'Mining',
            'Metalwork',
            'Marketing',
            'Machine Woodworking',
            'Literature in English',
            'Leatherwork',
            'Leather Goods',
            'Jewellery',
            'Islamic Religious Studies',
            'Integrated Science',
            'Insurance',
            'Information and Communication Technology',
            'Igbo',
            'Ibibio',
            'Home Management',
            'History',
            'Health Education or Health Science',
            'Hausa',
            'Gsm Phones Maintenance and Repairs',
            'Graphic Design',
            'Government',
            'Ghanaian Languages',
            'Geography',
            'General Mathematics or Mathematics',
            'General Knowledge in Art',
            'General Agriculture',
            'Garment Making',
            'Visual Art',
            'Upholstery',
            'Woodwork',
            'West African Traditional Religion',
            'Welding and Fabrication Engineering Craft Practice',
            'Yoruba',
        ];
    
        $SCIENCE = [
            'Mathematics',
            'English Language',
            'Physics',
            'Chemistry',
            'Biology',
            'Agricultural Science',
            'Civic Education',
            'Further Mathematics',
            'Technical Drawing',
            'Economics',
        ];
    
    
        $ART = [
            'English',
            'Mathematics',
            'Literature in English',
            'Government',
            'History',
            'Commerce',
            'Arabic',
            'Civic Education',
            'Biology or Agricultural Science',
            'Yoruba or Igbo or Hausa',
        ];



        //$subjects = array_unique(array_merge_recursive($NURSERY, $PRIMARY, $JSS, $WAEC));
        $subjects = array_unique(array_merge_recursive($NURSERY, $PRIMARY, $JSS, $WAEC));

        foreach($subjects as $subject){
            DB::table('subjects')->insert([
                'name' => $subject,
            ]);
        }
        
    }
}
