# pip install scikit-learn

from sklearn.feature_extraction.text import TfidfVectorizer
from sklearn.naive_bayes import MultinomialNB
from sklearn.model_selection import train_test_split
from sklearn.metrics import accuracy_score
from nltk.tokenize import word_tokenize
from nltk.probability import FreqDist
from nltk.corpus import stopwords
import string, sys

# Example dataset (journal entries and corresponding emotions)

if len(sys.argv) > 0:
    new_entry = [str(sys.argv[1]).lower()]

    data = [
        ("Today was incredible! I finally finished my project after weeks of hard work, and it turned out even better than I imagined. Seeing everything come together so smoothly filled me with so much pride. I feel like I’ve accomplished something truly meaningful, and it’s such a rewarding feeling.", "joy"),
        ("This morning’s walk was perfect. The sky was clear, the air felt fresh and cool, and my favorite playlist was on repeat. Every step felt lighter, and for the first time in a while, I felt genuinely happy just being in the moment. It reminded me of how much joy simple things can bring.", "joy"),
        ("I had the best catch-up with an old friend today. We haven’t spoken in months, but the moment we started talking, it felt like no time had passed. We laughed about old memories and made plans to meet soon. It’s moments like these that remind me how much connection brings happiness.", "joy"),
        ("The sunset tonight was absolutely breathtaking. The sky melted from soft oranges into deep pinks and purples, and I couldn’t help but just stand there and watch. Nature has such a beautiful way of bringing peace and joy, and I’m so grateful I took a moment to appreciate it.", "joy"),
        ("I received a surprise message from someone I’ve been thinking about lately. It was simple, just a friendly hello, but it made my whole day brighter. The fact that they thought of me too brought this unexpected wave of happiness.", "joy"),
        ("I tried a new recipe today, and it turned out amazing! The smell of freshly baked bread filled the whole house, and the taste was even better than I hoped for. Cooking has such a comforting, joyful feeling—especially when it all goes right.", "joy"),
        ("I spent the afternoon painting today, and it felt so freeing. I didn’t care about the outcome, just the process itself was so enjoyable. It’s been a long time since I lost myself in something creative, and I forgot how much happiness it brings.", "joy"),
        ("I finally finished that book I’ve been reading for weeks, and the ending left me smiling like an idiot. There’s something so satisfying about getting fully immersed in a story and feeling connected to the characters. Pure joy.", "joy"),
        ("I got to sleep in today without any alarms, and when I woke up, the sunlight was pouring through the windows. The calmness of the moment, mixed with the coziness of my bed, brought such a peaceful joy that lingered all day.", "joy"),
        ("I had a spontaneous dance party in my room today. Just me, my favorite songs, and absolutely no one watching. It felt silly but so freeing—sometimes joy is just letting yourself be ridiculous for a while.", "joy"),
        ("Today felt heavy. No specific reason, just a constant weight on my chest that I couldn’t shake off. I wish I knew how to make it stop.", "sadness"),
        ("I tried to stay busy, but the emptiness crept in anyway. It’s hard when even the things I used to enjoy don’t bring the same comfort anymore.", "sadness"),
        ("I thought I was doing better, but today brought back memories I wasn’t ready for. It’s exhausting to keep pretending I’m okay when I’m not.", "sadness"),
        ("I felt invisible today. Surrounded by people, yet completely alone. I wonder if anyone even notices when I’m struggling.", "sadness"),
        ("It hurts to care so much about people who don’t seem to feel the same. I keep giving my all, and it never feels like enough.", "sadness"),
        ("I miss the way things used to be. Lately, it feels like everything familiar is slipping away, and I don’t know how to hold on.", "sadness"),
        ("Another night spent overthinking every little thing I’ve said or done. I wish my mind would just let me rest for once.", "sadness"),
        ("It’s hard watching everyone else move forward while I feel stuck in the same place. I want to be happy for them, but it just reminds me of how lost I feel.", "sadness"),
        ("I heard a song today that reminded me of someone I’ve lost. The sadness hit me so suddenly, like no time had passed at all.", "sadness"),
        ("I’m tired of pretending everything’s fine. The mask I wear every day is starting to crack, and I don’t know how much longer I can keep it together.", "sadness"),
        ("I’m so frustrated today. No matter how much effort I put in, it feels like nothing ever works out the way it should. I keep trying to stay calm and push through, but the constant setbacks are wearing me down. It’s like I’m shouting into a void, and no one hears me. I’m tired of pretending it doesn’t bother me because it does—every single time.", "anger"),
        ("I’m beyond angry right now. Why do I always have to be the one to apologize first? It’s like people just expect me to fix everything, even when I’m not the one at fault. I’m tired of always being the one who compromises, who ‘keeps the peace.’ Maybe I don’t want to be the bigger person this time. Maybe I just want someone to meet me halfway for once.", "anger"),
        ("That conversation is still replaying in my head, and it makes me angrier every time I think about it. I should’ve spoken up, but I froze instead. Now I’m stuck with all these words I never said, all the things I wish I could go back and say. I hate feeling powerless in moments like that—it just makes the frustration linger even longer.", "anger"),
        ("I’m so sick of being underestimated. Every time I prove myself, it’s like people conveniently forget the effort it took to get here. I shouldn’t have to constantly fight for the respect I deserve, and yet here I am, again, feeling dismissed and overlooked. It’s exhausting and infuriating.", "anger"),
        ("The disrespect today was unbelievable. I don’t understand how some people can be so careless with their words. I keep thinking I should’ve called them out in the moment, but instead, I stayed quiet to avoid conflict. Now I’m left stewing in anger, wishing I had stood up for myself.", "anger"),
        ("I’m furious at how unfair everything feels right now. I put in all this effort, sacrificed my time, and gave everything I had—and for what? To be ignored like it doesn’t even matter? I’m tired of acting like it’s okay because it’s not. I deserve better than this.", "anger"),
        ("Why do people think it’s okay to cross boundaries just because I don’t immediately react? I stay calm to avoid drama, but that doesn’t mean I don’t notice or care. I’m done brushing things off just to make others comfortable. I have every right to be angry.", "anger"),
        ("I’m burning with frustration today. Every little thing feels like a spark, and I’m ready to snap. It’s not even about one specific thing—it’s just this buildup of stress, ignored feelings, and things left unsaid. I need to let this out somehow before it boils over completely.", "anger"),
        ("I trusted them, and they let me down—again. I don’t know why I keep giving chances to people who don’t deserve them. It’s like every time I forgive, it’s a signal that it’s okay to hurt me. I’m so angry at myself for letting it happen over and over.", "anger"),
        ("I’ve reached my limit. The constant interruptions, the dismissive tone, the subtle digs—it’s all adding up, and I’m done pretending it doesn’t bother me. I deserve respect, and if I don’t get it, maybe it’s time to walk away from people who don’t value me.", "anger"),
        ("I can’t stop thinking about how fake some people can be. The way they act so friendly to my face but twist things the moment I turn around—it’s disgusting. It makes me question why I ever trusted them in the first place. I feel sick just thinking about all the lies hidden under their smiles.", "disgust"),
        ("There’s something unsettling about fake compliments. Today, I overheard someone praising another person, only to mock them seconds later behind their back. That kind of two-faced behavior makes me feel physically uncomfortable—like I need to cleanse myself just from witnessing it.", "disgust"),
        ("I found myself judging someone today for something that wasn’t my business. The realization hit me hard, and I felt this wave of disgust—not toward them, but toward myself. That’s not the person I want to be. I need to work on showing more empathy, even in small, passing thoughts.", "disgust"),
        ("There’s a certain kind of disgust that comes from realizing how easily people follow the crowd. Today, I saw someone join in mocking another person just to fit in—it made my stomach turn. The need for belonging shouldn’t come at the cost of someone else’s dignity.", "disgust"),
        ("I opened the fridge today and was immediately hit by the smell of something rotten. I couldn’t figure out what it was at first, but when I found the mold-covered container shoved in the back, I nearly gagged. I can’t believe I let it sit there for so long without noticing.", "disgust"),
        ("There’s nothing worse than biting into what you think is a fresh piece of fruit, only to realize it’s gone bad. The taste of that sour, mushy apple stuck with me for hours. I don’t think I’ll be eating apples again for a while.", "disgust"),
        ("I found a hair in my food today—right in the middle of my meal. No matter how clean the restaurant looked, that single strand made everything feel contaminated. I couldn’t finish eating after that.", "disgust"),
        ("I watched someone spit on the street today without a second thought, and it instantly made my skin crawl. It’s such a simple thing, but the complete disregard for public spaces is just gross. Little things like that make me lose faith in basic manners.", "disgust"),
        ("The bathroom I had to use today was in such a horrible state—unflushed toilets, water all over the floor, and grime built up on every surface. I couldn’t even touch anything without feeling like I needed to wash my hands immediately afterward.", "disgust"),
        ("I overheard a conversation today where someone was bragging about not washing their bedsheets for months. Just thinking about all the bacteria and dirt that builds up made my stomach churn. It’s hard to believe people can be so careless about basic hygiene.", "disgust"),
        ("I woke up with a feeling of dread today, like something was wrong but I couldn’t figure out what. It’s that constant tightness in my chest that won’t go away, no matter how hard I try to ignore it. It’s like I’m waiting for something bad to happen, and I can’t shake the feeling that it’s just around the corner.", "fear"),
        ("I saw an accident today, and it shook me more than I expected. The thought that something like that could happen to anyone at any moment—it’s terrifying. I keep replaying the scene in my mind, and it feels like a shadow hanging over me. What if it happens to me or someone I care about?", "fear"),
        ("I had an interview today, and as soon as I sat down, I could feel my heart racing. The fear of messing up, of saying the wrong thing, of not being good enough—it's all-consuming. It’s like my body betrays me in those moments, and no matter how prepared I am, the fear always wins.", "fear"),
        ("I felt a sharp pang of fear last night when the power went out in the middle of the storm. Suddenly, the house felt too quiet, too still. I sat in the dark for what felt like forever, just listening to the wind howl outside, feeling completely vulnerable and isolated.", "fear"),
        ("I keep thinking about something someone said to me yesterday. It wasn’t even a big deal, but it’s been eating at me all day. What if I’ve misunderstood? What if it was a warning or a sign I missed? I can’t stop overthinking and imagining all the worst-case scenarios.", "fear"),
        ("There’s this growing fear that I’m losing control over things in my life. I used to be able to handle everything, but now, even the smallest tasks feel overwhelming. What if I can’t keep up anymore? The idea of falling behind, of not being able to manage it all, scares me more than I want to admit.", "fear"),
        ("I felt so alone today, and the fear of being completely isolated hit me like a wave. What if no one truly understands me? What if I can’t find my place in the world? The thought of being stuck in that loneliness is paralyzing.", "fear"),
        ("I had to walk home through an unfamiliar neighborhood today, and I couldn’t shake the fear that someone was following me. Every shadow felt threatening, every footstep felt too close. I kept checking over my shoulder, heart racing, wishing I was somewhere safe.", "fear"),
        ("I’m afraid of what’s happening with my health. The more I think about it, the more worried I get. I keep imagining the worst-case scenarios, and it’s almost like my mind can’t stop itself. What if it’s something serious? What if I don’t get better?", "fear"),
        ("There’s this fear of change that I can’t seem to escape. I’m not sure what’s coming next, and the uncertainty of it all makes me feel exposed. What if things don’t go the way I hope? What if I’m not prepared for what’s ahead? The unknown is terrifying.", "fear"),
        # Joy entries
        ("I woke up feeling refreshed and excited about the day ahead, as the sunrise painted the sky with brilliant hues and filled my heart with hope.", "joy"),
        ("Today I achieved a personal goal I've been working on for months, and the satisfaction of seeing my hard work pay off made me beam with joy.", "joy"),
        ("I had a delightful conversation with a stranger that turned into an unexpected friendship, leaving me feeling uplifted and optimistic.", "joy"),
        ("I spent the day immersed in nature, and the sound of birds chirping and the rustling of leaves brought an overwhelming sense of contentment and joy.", "joy"),
        ("After a long week, a simple cup of coffee shared with a loved one reminded me of life's small pleasures, filling me with quiet joy.", "joy"),

        # Sadness entries
        ("The news of a lost opportunity hit me hard today, leaving me with a deep sense of regret and sorrow that I couldn't shake off.", "sadness"),
        ("I sat alone in the quiet of the evening, feeling the weight of loneliness press upon me as memories of happier times flooded my mind.", "sadness"),
        ("A sudden wave of melancholy overtook me as I looked at old photographs, each image stirring up unresolved feelings of grief.", "sadness"),
        ("I tried to reach out for comfort, but the silence on the other end left me feeling more isolated and deeply saddened than before.", "sadness"),
        ("Every familiar place felt empty today, as the absence of laughter and warmth reminded me of what I had lost.", "sadness"),

        # Anger entries
        ("I was seething with anger today as I encountered unfair treatment, feeling powerless and enraged by the blatant disrespect.", "anger"),
        ("Every injustice I witnessed today ignited a spark of fury in me, and I felt compelled to speak out against the wrongs around me.", "anger"),
        ("The constant barrage of negative comments on my work left me feeling not only disheartened but burning with quiet anger.", "anger"),
        ("I tried to stay composed, but the repeated dismissals of my opinions eventually broke my patience, fueling an inner rage.", "anger"),
        ("The sense of betrayal I felt today was overwhelming, and the anger inside me grew with each reminder of the broken promises.", "anger"),

        # Disgust entries
        ("I was utterly repulsed today by the sight of garbage piling up on the streets, a stark reminder of how neglect can make even the ordinary seem revolting.", "disgust"),
        ("Witnessing the blatant disregard for hygiene in a public place filled me with disgust, leaving me questioning the norms of society.", "disgust"),
        ("I felt a wave of disgust when I discovered that the item I purchased was of such poor quality and carelessly made, betraying all my expectations.", "disgust"),
        ("Today, the overuse of chemicals in everyday cleaning products left me feeling uneasy and disgusted at the thought of what we expose ourselves to.", "disgust"),
        ("I encountered a disturbing scene at a restaurant that made me lose my appetite instantly, the careless treatment of food leaving a lingering sense of disgust.", "disgust"),

        # Fear entries
        ("I spent the night awake, haunted by unsettling dreams that left me gripped by fear long after waking up.", "fear"),
        ("The dark, stormy night brought an eerie silence that amplified my fear of the unknown lurking in every shadow.", "fear"),
        ("A sudden, unexplained noise in the middle of the night set my heart racing and filled me with a dread that I couldn't shake.", "fear"),
        ("I felt an overwhelming sense of vulnerability today when a series of unexpected events left me fearing for my safety.", "fear"),
        ("Every creak in the old house tonight seemed like a warning, intensifying my fear of what might be hiding in the darkness.", "fear"),

        # Additional Joy Entries (20)
        ("Just got promoted at work after years of dedication! My colleagues surprised me with cupcakes and kind notes. I feel so appreciated and excited for this new chapter.", "joy"),
        ("My sister gave birth to a healthy baby girl today! Holding my tiny niece for the first time filled me with overwhelming love and happiness.", "joy"),
        ("Won first place in the community baking competition! The look on my kids' faces when they announced my name was priceless.", "joy"),
        ("Finally reunited with my childhood best friend after 10 years apart. We picked up right where we left off - laughing until our sides hurt.", "joy"),
        ("Received a handwritten letter from my pen pal overseas. There's something magical about physical mail in this digital age.", "joy"),
        ("Completed my first marathon! The crowd's cheers and that final sprint across the finish line gave me the biggest adrenaline rush.", "joy"),
        ("Surprised my partner with a homemade candlelit dinner. Their joyful reaction made all the preparation completely worth it.", "joy"),
        ("Discovered a nest of baby birds in our backyard tree. Watching their mother care for them brings me quiet joy every morning.", "joy"),
        ("My students threw me a surprise thank-you party today. Seeing how much they've grown and learned fills me with teacher pride.", "joy"),
        ("Finally perfected my grandmother's cookie recipe! The familiar smell transported me back to her cozy kitchen.", "joy"),
        ("Witnessed my toddler take their first steps today! That wobbly determination and triumphant grin melted my heart.", "joy"),
        ("Received a scholarship for my dream program! All those late-night study sessions finally paid off.", "joy"),
        ("Spontaneous road trip with friends led to watching the sunrise over the mountains. Pure magic.", "joy"),
        ("My rescue dog finally learned to trust humans again. Seeing her wag her tail without fear brings tears of happiness.", "joy"),
        ("Community garden harvest day! Sharing fresh vegetables with neighbors created such a warm sense of connection.", "joy"),
        ("Heard my favorite childhood song on the radio today. Danced around the kitchen like nobody was watching.", "joy"),
        ("Found $20 in an old jacket pocket! Treated myself to fancy coffee and donated the rest - double happiness.", "joy"),
        ("Finished knitting my first sweater! Imperfections and all, I'm ridiculously proud of this cozy creation.", "joy"),
        ("Random stranger paid for my coffee this morning. Their kindness started a chain reaction of good moods.", "joy"),
        ("Caught the exact moment a shooting star streaked across the night sky. Made a wish and felt cosmic connection.", "joy"),

        # Additional Sadness Entries (15)
        ("Found old photos of us smiling together. It hurts remembering how easily we used to laugh.", "sadness"),
        ("Another birthday alone. The silence in my apartment feels heavier than usual today.", "sadness"),
        ("Visited Mom's grave for the first time since the funeral. The finality of it all crashed over me anew.", "sadness"),
        ("Failed my driving test again. The disappointment feels like a physical weight dragging me down.", "sadness"),
        ("Overheard friends making plans without me. That familiar ache of exclusion settled in my chest.", "sadness"),
        ("Our family home sold today. Saying goodbye to every memory-filled room broke something inside me.", "sadness"),
        ("The doctor's office called with test results. I've never felt more vulnerable and scared.", "sadness"),
        ("Deleted our text thread. Thousands of messages reduced to empty space in my phone and heart.", "sadness"),
        ("Rainy days always make me melancholy. Each droplet echoes the tears I won't let myself cry.", "sadness"),
        ("Saw your favorite flowers blooming. Nature's persistence feels like cruel mockery of my grief.", "sadness"),
        ("Found the love letter you never sent. Now I'm mourning a future that never was.", "sadness"),
        ("My childhood pet passed away today. The house feels unbearably quiet without her padding around.", "sadness"),
        ("Realized I've become someone I don't recognize. When did I lose myself so completely?", "sadness"),
        ("First holiday season after the divorce. Empty chair at the table screams louder than any conversation.", "sadness"),
        ("Chronic pain flare-up today. The relentless discomfort makes hope feel out of reach.", "sadness"),

        # Additional Anger Entries (10)
        ("Caught my partner in another lie. The betrayal burns hotter each time I replay the conversation.", "anger"),
        ("Neighbor's dog destroyed my garden again. Their careless 'boys will be boys' shrug made me see red.", "anger"),
        ("Boss took credit for my idea in the big meeting. Smug smile while presenting my work made me furious.", "anger"),
        ("Customer spat on me during their tantrum. No amount of 'service with a smile' justifies this disrespect.", "anger"),
        ("Parents dismissed my career choices again. Years of condescension finally boiled over into rage.", "anger"),
        ("Insurance denied my claim for the third time. Bureaucratic runaround is testing my last nerve.", "anger"),
        ("Roommate ate my special diet food again. Their casual 'I'll replace it' ignores the planning it requires.", "anger"),
        ("Car got keyed in the parking lot. Senseless vandalism leaves me shaking with frustration.", "anger"),
        ("Teacher accused my child of cheating without proof. Defending them against bias made my blood boil.", "anger"),
        ("Political ads filled with blatant lies. The manipulation makes me furious at our broken system.", "anger"),

        # Additional Disgust Entries (8)
        ("Found maggots in the neglected office fridge. The squirming mass made me retch instantly.", "disgust"),
        ("Witnessed someone toss trash from their car window. Such environmental disregard turns my stomach.", "disgust"),
        ("Stepped in dog poop because owners couldn't be bothered to clean up. The smell lingered for hours.", "disgust"),
        ("Overheard racist remarks at the grocery store. The hateful words left a bitter taste in my mouth.", "disgust"),
        ("Public restroom stall walls covered in crude drawings. The violation of shared space sickens me.", "disgust"),
        ("Date showed up with food stuck in their teeth and didn't care. Basic hygiene matters!", "disgust"),
        ("Found mold growing under the sink. The fuzzy patches and musty smell triggered my gag reflex.", "disgust"),
        ("Saw teenagers torturing a stray cat. Their cruel laughter made me physically ill with disgust.", "disgust"),

        # Additional Fear Entries (7)
        ("Heard strange noises downstairs at 3 AM. Frozen in bed, every creak amplified my panic.", "fear"),
        ("Turbulence got so bad I thought the plane would break apart. Death felt terrifyingly close.", "fear"),
        ("Stalker showed up at my workplace again. Constant looking over my shoulder is exhausting.", "fear"),
        ("Child wandered off in the crowded mall. Those five minutes before finding them were pure terror.", "fear"),
        ("Earthquake aftershocks keep coming. Each tremor reignites that primal survival fear.", "fear"),
        ("Suspicious mole appeared suddenly. Google searches spiral into worst-case scenarios.", "fear"),
        ("Layoff rumors at the office. The uncertainty about providing for my family keeps me awake.", "fear"),
        
        # Joy entries
        ("Today, I couldn't stop smiling. The simple pleasure of a shared laugh with a friend made the day feel magical.", "joy"),
        ("I received a heartfelt compliment that warmed my spirit and reminded me that I am appreciated.", "joy"),
        ("I danced in the rain and felt free, as each drop seemed to wash away my worries, filling me with joy.", "joy"),
        ("The surprise birthday party planned by my family filled me with immense joy and gratitude for their love.", "joy"),
        ("A spontaneous road trip with loved ones left me with unforgettable memories and a heart full of joy.", "joy"),
        ("My morning coffee tasted extra special today, and every sip reminded me of the simple pleasures in life.", "joy"),
        ("After a challenging week, the unexpected call from an old friend lifted my spirits and filled me with joy.", "joy"),
        ("I spent the afternoon immersed in my favorite hobby, and the creative process brought a genuine smile to my face.", "joy"),
        ("Seeing the beauty of nature on my walk—flowers blooming, birds chirping—filled me with an uplifting sense of joy.", "joy"),
        ("I felt an overwhelming sense of gratitude and joy when I realized how loved and supported I am by those around me.", "joy"),

        # Sadness entries
        ("Today, I felt a deep sense of melancholy as I remembered a lost loved one and the moments we once shared.", "sadness"),
        ("The quiet emptiness of my room echoed my inner sorrow, a stark reminder of loneliness.", "sadness"),
        ("A series of setbacks left me feeling defeated and overwhelmed with sadness that I struggled to overcome.", "sadness"),
        ("I saw an old friend suffering, and the pain in their eyes mirrored my own hidden sadness.", "sadness"),
        ("An unexpected cancellation of plans left me alone with my thoughts, and the heaviness of sadness settled in.", "sadness"),
        ("Every time I pass by that old park, memories of better days bring a wave of bittersweet sadness.", "sadness"),
        ("I felt isolated today, as if I were trapped in a bubble of sorrow that nothing could burst.", "sadness"),
        ("The loss of a small but meaningful opportunity left me with an aching sadness that lingered throughout the day.", "sadness"),
        ("I spent the evening reflecting on past regrets, each memory deepening the sadness that was hard to shake.", "sadness"),
        ("The absence of a familiar voice in my life today reminded me of the sadness of loneliness.", "sadness"),

        # Anger entries
        ("I felt a surge of anger when I discovered that my hard work was taken for granted without acknowledgment.", "anger"),
        ("Seeing injustice in action today left me fuming with anger, compelling me to fight for what's right.", "anger"),
        ("I was irritated by repeated disruptions, and the simmering anger within me grew until I could no longer remain silent.", "anger"),
        ("A broken promise from someone I trusted sparked a blaze of anger, leaving me questioning their integrity.", "anger"),
        ("The constant barrage of negative news made me feel helpless and angry about the state of the world.", "anger"),
        ("I was livid when a close friend betrayed my trust, and the raw anger nearly overwhelmed me.", "anger"),
        ("Every dismissive remark today added fuel to my growing anger, making it hard to keep my composure.", "anger"),
        ("I felt enraged by the blatant disrespect shown to me in a meeting, and every word stoked my fury.", "anger"),
        ("The unfair treatment of a coworker ignited a sense of righteous anger in me, urging me to speak up.", "anger"),
        ("A series of petty injustices left me so angry that I needed to step away to collect my thoughts.", "anger"),

        # Disgust entries
        ("I felt an overwhelming sense of disgust when I encountered blatant dishonesty during a conversation today.", "disgust"),
        ("The careless disregard for the environment left me feeling disgusted and frustrated by the lack of responsibility.", "disgust"),
        ("A repulsive display of cruelty towards animals shook me, filling me with a deep sense of disgust and sorrow.", "disgust"),
        ("Witnessing the unhygienic conditions of the public restroom made me recoil in disgust, questioning basic standards.", "disgust"),
        ("I was taken aback by the insincere flattery I heard today, which left a bitter taste of disgust in my mouth.", "disgust"),
        ("Seeing how waste was carelessly discarded in our local park filled me with disgust at the lack of community care.", "disgust"),
        ("The overwhelming stench of spoiled food in the market made me step back in disgust, unable to stomach the sight.", "disgust"),
        ("I felt repulsed by the blatant disregard for basic manners at the dinner table, leaving me with a sense of disgust.", "disgust"),
        ("The overuse of chemicals in everyday products left me feeling uneasy and disgusted by modern lifestyles.", "disgust"),
        ("I encountered a disturbing act of vandalism today that made me feel an intense disgust for the destruction of beauty.", "disgust"),

        # Fear entries
        ("Walking home alone at night filled me with a paralyzing fear, as every shadow seemed to hide a potential threat.", "fear"),
        ("I woke up in a cold sweat, gripped by fear after a nightmare that felt all too real.", "fear"),
        ("The uncertainty of my future left me trembling with fear, as the unknown path ahead seemed daunting.", "fear"),
        ("During a severe storm, the howling wind and crashing thunder instilled a raw fear that was hard to shake.", "fear"),
        ("A sudden, unexplained noise in the dark left me frozen with fear, questioning every sound around me.", "fear"),
        ("The eerie silence in the abandoned building filled me with a creeping fear that I couldn't easily dismiss.", "fear"),
        ("Watching a horror movie alone in the dark intensified my fear, making every creak of the house sound ominous.", "fear"),
        ("I felt a cold dread wash over me as I received unexpected news that hinted at looming danger.", "fear"),
        ("The thought of confronting a long-avoided problem filled me with fear, as uncertainty gripped my mind.", "fear"),
        ("A sudden change in the atmosphere during my evening walk made me feel as though something was terribly amiss, and fear took hold.", "fear"),

        # Joy Additions (25)
        ("My artwork got selected for the local gallery exhibition! Seeing my creation framed on the wall with a little red dot 'sold' sticker - I'm floating with happiness!", "joy"),
        ("Surprise anniversary getaway! Woke up to packed bags and a mystery destination. The childlike excitement reminds me why I fell in love.", "joy"),
        ("Community theater standing ovation! Never imagined I'd act again after college. That rush of shared energy with the audience - pure magic.", "joy"),
        ("First garden tomato of the season! Sun-warmed and bursting with flavor that no store-bought fruit could ever match. Simple earthy joy.", "joy"),
        ("My immigration paperwork finally approved! Holding that official document, I feel like I can finally plant roots in this new home.", "joy"),
        ("Baby's first belly laugh today! That infectious giggle turned our stressful day into instant sunshine. Recorded it immediately to cherish forever.", "joy"),
        ("Finished building the treehouse with my dad! Smell of fresh-cut wood and our high-five at the top - core memory unlocked.", "joy"),
        ("Random dance party erupted at the bus stop! Strangers laughing together as we grooved to someone's portable speaker. Humanity at its best.", "joy"),
        ("Opened my bakery's doors for the first time today! The bell jingling as customers entered my dream-come-true space - overwhelming joy.", "joy"),
        ("Library notified me they're stocking my self-published book! Knowing my words will sit on those hallowed shelves - validation beyond measure.", "joy"),
        ("Reconnected with my birth family through DNA testing! Their warm embrace erased decades of wondering. Complete in ways I never imagined.", "joy"),
        ("Caught the last train home by literally seconds! Collapsed into my seat laughing at the ridiculous sprint, heart pounding with grateful joy.", "joy"),
        ("First wheelchair basketball game since my accident! The familiar thrill of competition mixed with newfound community - triumphant joy.", "joy"),
        ("Neighborhood kids made me 'best lemonade stand customer' crown! Their beaming pride in that wobbly construction paper masterpiece - pure delight.", "joy"),
        ("Northern lights danced across the sky tonight! That otherworldly green glow left our whole group speechless with wonder.", "joy"),
        ("My prototype actually worked! After 47 failed attempts, that blinking green light felt like winning the Nobel Prize.", "joy"),
        ("Adopted cat finally climbed onto my lap! Three months of patience rewarded with rumbling purrs. Mutual trust achieved.", "joy"),
        ("Toddler 'read' me their first made-up story! Scribbled pages turned with dramatic flourishes - future novelist in the making.", "joy"),
        ("Crossed the finish line of my chemotherapy! Ringing that remission bell echoed through the hospital halls - victory over darkness.", "joy"),
        ("College acceptance letter arrived! My immigrant parents' tears of pride made every late-night study session worthwhile.", "joy"),
        ("Reached the mountain summit at dawn! Crisp air burning my lungs as golden light spilled over endless peaks - alive in every cell.", "joy"),
        ("Surprise marriage proposal during our weekly grocery run! Said yes between the cereal aisle and frozen veggies - perfectly us.", "joy"),
        ("Retirement countdown: 1 day left! Colleagues decorated my desk with memories from 30 years. Grateful closure to this chapter.", "joy"),
        ("Heard my song on the radio for the first time! Screamed so loud I scared the cat. Called everyone I know to tune in.", "joy"),
        ("Community came together to rebuild after the flood. Hammers swinging and laughter rising from the rubble - hope made tangible.", "joy"),

        # Sadness Additions (20)
        ("Empty mailbox again. Another month without hearing from my deployed son. The silence feels like physical ache.", "sadness"),
        ("Putting Grandma's wedding ring in the donation box. Letting go of heirlooms feels like losing her all over again.", "sadness"),
        ("Autumn leaves swirling in the playground. Same place we scattered his ashes. Grief comes in waves with each changing season.", "sadness"),
        ("Deleted your number today. 14 years of memories reduced to 'confirm permanent deletion'. Digital graveyard of us.", "sadness"),
        ("Negative pregnancy test - again. That monthly hope crushed feels like my body's cruel betrayal.", "sadness"),
        ("Found Mom's favorite recipe card stained with her handwriting. Couldn't bring myself to cook it - the loneliness would be too sharp.", "sadness"),
        ("My piano sits silent since the arthritis diagnosis. Fingers tracing silent keys remembering Chopin etudes now beyond reach.", "sadness"),
        ("School reunion photos flooded social media. Everyone's life milestones highlight my stagnant existence.", "sadness"),
        ("Cancelled wedding venue today. Each call to vendors feels like peeling off pieces of my future.", "sadness"),
        ("Dementia stole Dad's recognition today. His confused 'Who are you?' shattered what remained of my composure.", "sadness"),
        ("Final child moved out. Empty nest echoes with phantom laughter and unanswered 'Mom!' calls.", "sadness"),
        ("Saw my ex with their new partner. That casual handhold felt like a punch to the solar plexus.", "sadness"),
        ("Bank denied the small business loan. Watching my entrepreneurial dreams evaporate with each form rejection.", "sadness"),
        ("Chronic illness canceled my marathon plans. The race medal mocks me from its unused hook.", "sadness"),
        ("Burned dinner again. Such a small failure, but it cracked the fragile dam holding back today's accumulated sorrows.", "sadness"),
        ("My students graduated today. Proud of them, but already mourning the classroom energy they took with them.", "sadness"),
        ("First birthday without your morning call. Kept checking my phone until battery died, hoping against logic.", "sadness"),
        ("Relapsed after 18 months sober. The shame tastes more bitter than the liquor ever did.", "sadness"),
        ("Eviction notice taped to my door. Each box packed feels like burying pieces of my stability.", "sadness"),
        ("Scatter ashes at sea tomorrow. How do you say final goodbye to someone who was your entire world?", "sadness"),

        # Anger Additions (15)
        ("Landlord raised rent 40% overnight! Exploiting the housing crisis makes my blood boil with helpless rage.", "anger"),
        ("Sexist comment in the boardroom again. Colleagues' uncomfortable silence louder than the offender's words.", "anger"),
        ("Insurance denied my disability claim. Years of premiums for nothing when I need support most!", "anger"),
        ("Sister stole from our dying mother's account. Family betrayal cuts deeper than any stranger's theft.", "anger"),
        ("Political robocalls during my father's funeral. How dare they violate grief with propaganda!", "anger"),
        ("Boss changed deadline after I worked all weekend. Moving goalposts reveals complete disrespect for my time.", "anger"),
        ("Online trolls attacked my disabled child's photo. Keyboard warriors forgetting there's a human behind the screen.", "anger"),
        ("Police dismissed my harassment complaint. Being told 'boys will bmail not founde boys' in 2024 is infuriating.", "anger"),
        ("Plagiarized my research paper! University's slow response protects the perpetrator over the victim.", "anger"),
        ("Neighbors blasted music through my chemo recovery. Their 'right to party' overrides basic human decency.", "anger"),
        ("Date spiked my drink. Survival anger mixes with disgust at their predatory behavior.", "anger"),
        ("Teacher labeled my son 'problem child' without accommodation. Institutional failure crushing his potential.", "anger"),
        ("Mechanic scammed my elderly mother. Exploiting trust makes me livid with protective fury.", "anger"),
        ("Parking spot stolen by someone watching me wait. Their smug wave as they exited the car...", "anger"),
        ("Medical bill for $5k - service I never received! Endless phone trees can't fix their billing error.", "anger"),

        # Disgust Additions (10)
        ("Public pool's cloudy water revealed floating bandaids. Immediate exit with skin crawling.", "disgust"),
        ("Found cockroach legs in restaurant food. Manager's dismissive shrug ensured I'll never return.", "disgust"),
        ("Roommate's moldy dishes pile grew sentient. The fuzzy green growth haunts my nightmares.", "disgust"),
        ("Stepped on used syringe at the playground. Fear and revulsion for my child's safety collided.", "disgust"),
        ("Dentist found 6 cavities from soda addiction. Self-disgust at my neglected oral health.", "disgust"),
        ("Homophobic graffiti in the school bathroom. Hate symbols carved into stalls turn my stomach.", "disgust"),
        ("Found maggot-infested meat at the supermarket. Employees acted like it was normal - never shopping there again.", "disgust"),
        ("Date's unwashed body odor overwhelmed the car. Held breath while counting down to escape.", "disgust"),
        ("Child's lice outbreak letter came home. Phantom itching just thinking about those parasites.", "disgust"),
        ("Corruption trial reveals politician's embezzlement. Moral disgust at stolen education funds.", "disgust"),

        # Fear Additions (10)
        ("Positive COVID test before transplant surgery. Dreams of health crumbling with each cough.", "fear"),
        ("Strange men followed me to my car. Pretended to phone someone while fumbling with shaking keys.", "fear"),
        ("Breast biopsy tomorrow. Google statistics loop in my mind despite doctor's reassurance.", "fear"),
        ("Earthquake cracked our apartment wall. Aftershocks make sleep impossible - every creak terrifies.", "fear"),
        ("Child's high fever spiked to 104°. ER waiting room minutes felt like lifetimes of dread.", "fear"),
        ("Layoff rumors confirmed. Mortgage payments loom while job market collapses - survival fear.", "fear"),
        ("Stranger accessed my baby monitor feed. Violation of our safe space induces paranoid checking.", "fear"),
        ("Hurricane path shifts toward us. Boarding windows while weather channel's doom loop plays.", "fear"),
        ("AI replacement talk at work. Middle-aged career obsolescence panic sets in.", "fear"),
        ("Mysterious lump discovered during shower. Waiting for tests feels like suspended animation.", "fear"),

        # Joy entries (including keywords "joy", "happy", "cheerful")
        ("I felt a surge of joy and happiness when I saw my friend; the day was incredibly cheerful and full of life.", "joy"),
        ("Today was a happy day, and every moment was filled with pure joy that made my spirit soar.", "joy"),
        ("The joyous atmosphere at the festival left me feeling truly joyful and uplifted.", "joy"),
        ("I received wonderful news that made me feel exceptionally happy, spreading joy all around.", "joy"),
        ("A kind gesture brought a smile to my face, and I was overwhelmed with joy and a happy heart.", "joy"),
        
        # Sadness entries (including keywords "sad", "sorrow", "down")
        ("I felt deeply sad today, with a heavy heart and a lingering sorrow that I couldn't shake off.", "sadness"),
        ("Every familiar place reminded me of past losses, making me feel profoundly sad and melancholic.", "sadness"),
        ("The quiet of the evening only amplified my sadness, leaving me feeling down and isolated.", "sadness"),
        ("A somber mood took over as I remembered old times, and I couldn't help but feel sad and reflective.", "sadness"),
        ("Today was a day filled with sadness and a sense of sorrow that dimmed even the brightest moments.", "sadness"),
        
        # Anger entries (including keywords "angry", "anger", "irate")
        ("I was extremely angry when I discovered the betrayal, a burning anger that I just couldn’t contain.", "anger"),
        ("Every injustice made me feel more and more angry, and the anger inside me grew into a fierce determination.", "anger"),
        ("I found myself irate and filled with anger after dealing with constant disrespect throughout the day.", "anger"),
        ("My heart raced with anger as I faced unfair treatment, and I felt an overwhelming surge of angry frustration.", "anger"),
        ("The day's events left me boiling with anger, and I couldn’t hide how angry and outraged I felt.", "anger"),
        
        # Disgust entries (including keywords "disgust", "disgusted", "repulsed")
        ("I was utterly disgusted by the sight of the filthy conditions, and the word 'disgust' barely begins to describe my feelings.", "disgust"),
        ("The blatant disregard for hygiene left me with an intense sense of disgust and repulsion.", "disgust"),
        ("I felt a wave of disgust as I witnessed behavior that was not only careless but truly repulsive.", "disgust"),
        ("Every detail of the situation made me feel disgusted, a deep and undeniable disgust that made me step back.", "disgust"),
        ("The actions I saw were so unsanitary that I was filled with an overwhelming disgust and a strong urge to get away.", "disgust"),
        
        # Fear entries (including keywords "fear", "scared", "afraid")
        ("A cold shiver of fear ran down my spine as I heard the unexplained noises in the dark, leaving me truly scared.", "fear"),
        ("I was gripped by fear today, and every shadow seemed to magnify the feeling of being afraid.", "fear"),
        ("The eerie silence in the empty hallway filled me with an unsettling fear and left me feeling scared.", "fear"),
        ("I felt a deep and paralyzing fear as I confronted the unknown, a fear that made me extremely anxious and afraid.", "fear"),
        ("Every creak of the old house triggered a fresh bout of fear, and I found myself too scared to move.", "fear"),

        # Joy (10 entries)
        ("The joy in my heart overflowed when I saw my daughter take her first steps - her giggles were pure sunshine.", "joy"),
        ("Joyful tears streamed down my face as I hugged my brother returning from deployment. Five years apart vanished in that embrace.", "joy"),
        ("Baking cookies with Grandma filled the kitchen with joy - flour fights and her raspy laugh echoing through the house.", "joy"),
        ("A spontaneous road trip with friends brought unexpected joy - singing off-key to 90s hits under starry skies.", "joy"),
        ("My students threw a surprise party shouting 'We love you!' - their joyful cheers melted my teacher-tired soul.", "joy"),
        ("Finding my lost wedding ring in the garden soil sparked joy so intense I danced barefoot in the rain.", "joy"),
        ("The joy of adopting our rescue dog was unreal - watching her finally trust humans again healed us both.", "joy"),
        ("Joy bubbled up as I crossed the marathon finish line - every aching muscle worth it for that triumphant moment.", "joy"),
        ("Hearing my song on the radio for the first time brought joyful disbelief - I screamed until my voice cracked!", "joy"),
        ("Simple joy: morning coffee on the porch, birdsong symphony, and the lavender bush blooming purple perfection.", "joy"),

        # Sadness (10 entries)
        ("Sadness weighs me down today like wet concrete - even getting out of bed feels impossible.", "sadness"),
        ("The sad truth hit: Mom doesn’t recognize me anymore. Her vacant stare crushed what was left of my hope.", "sadness"),
        ("Sadness clings like fog after the divorce papers arrived. Our wedding photo mocks me from the dresser.", "sadness"),
        ("Empty mailbox again. The sad reality sinks in - he’s not writing back. Maybe never will.", "sadness"),
        ("Sad doesn’t begin to describe it. Holding my stillborn niece - dreams buried in a pink casket.", "sadness"),
        ("Graduation day should be happy, but sadness drowns me. Without Dad here to see it, what’s the point?", "sadness"),
        ("Chronic pain’s sad routine: cancelled plans, isolation, and watching life through hospital windows.", "sadness"),
        ("Sadness festers as I pack childhood toys. Letting go of innocence hurts more than I expected.", "sadness"),
        ("The vet said ‘It’s time.’ My sad hands shook as I petted my old cat goodbye one last time.", "sadness"),
        ("Sad realization: I’ve become the bitter person younger me swore I’d never be.", "sadness"),

        # Anger (10 entries)
        ("White-hot anger surged when my credit card was declined - the bank ‘accidentally’ froze my account.", "anger"),
        ("Angry tears burned as they mocked my accent. ‘Go back home’ isn’t just rude - it’s dehumanizing.", "anger"),
        ("Anger vibrates in my bones. My ex took the dog, the records, even my grandmother’s quilt.", "anger"),
        ("The landlord’s smirk ignited rage - 30% rent hike with two days’ notice. Legal? Maybe. Cruel? Absolutely.", "anger"),
        ("Angry doesn’t cover it. They promoted the guy who stole my ideas. Corporate loyalty is a joke.", "anger"),
        ("My hands shook with rage watching him lie to the committee. How many careers has that narcissist ruined?", "anger"),
        ("Anger tastes metallic. Five years of ‘We’re family here!’ then laid off via automated email.", "anger"),
        ("Furious at myself - fell for the same empty promises again. When will I learn?", "anger"),
        ("Passive-aggressive Post-it notes from my roommate - anger simmering with each petty complaint.", "anger"),
        ("Rage exploded when the tow truck took my legally parked car. Clerk shrugged: ‘System error.’", "anger"),

        # Disgust (10 entries)
        ("Disgust choked me. The ‘fresh’ chicken oozed gray slime. Store manager’s shrug was almost worse.", "disgust"),
        ("Disgusting discovery: bedbugs in the hotel sheets. My skin still crawls 24 hours later.", "disgust"),
        ("Moral disgust: watching coworkers laugh at racist memes. Their ‘jokes’ made me physically ill.", "disgust"),
        ("Revolting cafeteria food - gristle in gravy and a dead fly floating in the soup. Ate crackers instead.", "disgust"),
        ("Disgusted by my reflection - binge-eating again, chocolate smears on the mirror. Pathetic.", "disgust"),
        ("Found his dating profile while engaged. Disgust curdles my stomach - who was that man?", "disgust"),
        ("Sickening hypocrisy: politicians preaching ‘family values’ while cheating on their spouses.", "disgust"),
        ("Disgusting bathroom at the gas station - urine puddles and used needles. Held my breath the whole time.", "disgust"),
        ("Revolting realization: He’d been stealing from the charity fund. Donors’ trust meant nothing.", "disgust"),
        ("Disgust overwhelmed me. The kitten’s injured leg was intentional - some people are monsters.", "disgust"),

        # Fear (10 entries)
        ("Fear paralyzed me in the MRI tube - claustrophobic panic rising with every mechanical click.", "fear"),
        ("Terrified of my own mind. These dark thoughts can’t be normal. What’s wrong with me?", "fear"),
        ("Fear spiked when the cop lights flashed behind me. Did I signal? Breathe. Don’t make sudden moves.", "fear"),
        ("Panic attack in the grocery aisle - heart racing, vision blurring. Had to abandon the cart.", "fear"),
        ("Fear whispers: ‘What if the cancer’s back?’ Every ache becomes a death sentence.", "fear"),
        ("Terror at 3 AM - someone trying to jimmy the patio door. Still shaking hours later.", "fear"),
        ("Fear of failure chains me to bed. What if I try and everyone sees I’m a fraud?", "fear"),
        ("Dread coils in my gut. Layoff list posts tomorrow. Mortgage. Kids’ tuition. Breathe.", "fear"),
        ("Fear made me cruel. Snapped at my daughter over spilled milk - scared I’m becoming Mom.", "fear"),
        ("Terrifying diagnosis: ‘High-risk pregnancy.’ Every cramp sends me Googling worst cases.", "fear"),

        # Joy entries (keywords: ecstatic, amazing, joyful, happiness)
        ("I was ecstatic today, bursting with joy and a happiness that lit up every moment.", "joy"),
        ("Everything felt amazing as I embraced each moment with pure, undeniable joy.", "joy"),
        ("Today, I was so happy that every smile and laugh radiated pure joy.", "joy"),
        ("I experienced an overwhelming joy that turned even the simplest moments into celebrations.", "joy"),
        ("Every encounter left me feeling deeply joyful and filled with immense happiness.", "joy"),
        
        # Sadness entries (keywords: sad, sorrow, depressed, melancholy)
        ("I felt profoundly sad today, with every moment drenched in sorrow and deep melancholy.", "sadness"),
        ("The sadness hit me hard—intense and leaving me feeling utterly down.", "sadness"),
        ("Every memory brought a wave of sadness, making the present feel unbearably sorrowful.", "sadness"),
        ("Today, I felt so depressed that even the light of day couldn’t chase away my sadness.", "sadness"),
        ("I was engulfed in a deep, sorrowful emptiness that left every thought tinged with melancholy.", "sadness"),
        
        # Anger entries (keywords: angry, furious, livid, rage)
        ("I was livid today, seething with anger over every injustice I encountered.", "anger"),
        ("Every slight pushed me over the edge, and I was extremely angry with a rage nearly explosive.", "anger"),
        ("The constant challenges left me feeling incredibly angry, with frustration bubbling over every injustice.", "anger"),
        ("I was furious at the blatant disrespect thrown my way, my anger reaching a boiling point.", "anger"),
        ("My anger was off the charts today, with every unfair word igniting a burning rage inside me.", "anger"),
        
        # Disgust entries (keywords: disgust, repulsed, revolting)
        ("I was absolutely repulsed by the disgusting behavior I witnessed, every detail triggering deep disgust.", "disgust"),
        ("The filthy conditions and blatant disregard for hygiene left me feeling profoundly disgusted and sick.", "disgust"),
        ("Every sight and smell was revolting, and I felt a powerful disgust that I couldn’t ignore.", "disgust"),
        ("I was so disgusted by the utter lack of decency and hygiene that I nearly lost my appetite.", "disgust"),
        ("The display of filth and careless behavior left me absolutely disgusted.", "disgust"),
        
        # Fear entries (keywords: terrified, scared, fear, anxious)
        ("I was terrified today, every creak and shadow in the dark amplifying my fear.", "fear"),
        ("The eerie silence and unknown threats had me so scared that I couldn’t shake the terror.", "fear"),
        ("Every unexpected sound in the night made me jump, overwhelmed by a raw fear.", "fear"),
        ("I was gripped by an all-consuming fear that froze me in place, every moment a nightmare.", "fear"),
        ("The constant feeling of impending doom left me terrified and anxious for my safety.", "fear"),
        
        # Joy (10 entries)
        ("Pure absolute joy - landed my dream job today! Cried happy tears in the parking lot, didn't mind who saw.", "joy"),
        ("Joy so intense it aches - held my newborn niece and she gripped my finger. Never felt love this powerfully before.", "joy"),
        ("After years of rejection, my novel got accepted. Joyful cheering startled the neighbors, but I didn't care.", "joy"),
        ("Joyful chaos: kids covered in mud, dog barking, partner dancing freely. This imperfect perfection is everything.", "joy"),
        ("Received the all-clear from oncology today. Joy tastes like champagne and grateful tears mixed together.", "joy"),
        ("Joyful moment: found $50 in old jeans, bought tacos for a homeless man. His smile made my entire decade.", "joy"),
        ("Shouted with excitement when I summited the peak. Joy shone brighter than the sunrise over the valley.", "joy"),
        ("Joyful celebration - surprise engagement party. Best friend tripped into the roses, but nothing dampens this happiness!", "joy"),
        ("Nailed the audition! Director said 'That was pure magic.' Joy hums through me like electricity.", "joy"),
        ("Overflowing joy: first Pride parade since coming out. Sea of rainbows and empowering chants. Felt like home.", "joy"),

        # Sadness (10 entries)
        ("Sadness anchors me down. Can't move, can't breathe. Just stare blankly at the wall.", "sadness"),
        ("Found Mom's old scarf. Her scent remains. Sadness hit so hard I nearly collapsed.", "sadness"),
        ("Heartbreaking moment: my child asked why Daddy doesn't live here anymore. Swallowed tears to respond.", "sadness"),
        ("Chronic pain's heavy rhythm: cancel plans again, take medication, resent my failing body.", "sadness"),
        ("Sadness spreads like ink. Can't explain to friends - 'But you have everything!' They don't see the void.", "sadness"),
        ("Shelter called about my old dog's passing. Sadness tastes like his last treat still in my pocket.", "sadness"),
        ("Graduation photos everywhere. Painful truth: I dropped out for Dad's medical bills. Proud but aching.", "sadness"),
        ("Agony watching her drive away. Five years ended with 'I need space.' Space meant someone else.", "sadness"),
        ("Third miscarriage. Doctor said 'Try again.' Sadness isn't something you simply reset.", "sadness"),
        ("Sadness consumed me today. Dishes piled up, calls ignored. Basic existence feels overwhelming.", "sadness"),

        # Anger (10 entries)
        ("Burning rage - boss claimed credit for my presentation again. Corporate politics at its worst.", "anger"),
        ("Furious over roommate eating my last snack. Seems petty, but it's the final straw.", "anger"),
        ("Anger simmers: 'Just a joke!' they said after mocking my disability. Your humor hurts.", "anger"),
        ("Blind rage discovering the texts. Three years married, him sending inappropriate photos to an intern.", "anger"),
        ("Fuming mad - car towed during medical appointment. 'No exceptions' from heartless bureaucracy.", "anger"),
        ("Anger tightens my jaw. Police 'lost' my assault report. Again. System failure.", "anger"),
        ("Slammed my fist (regrettably). Landlord hikes rent while ignoring black mold. Unethical greed.", "anger"),
        ("Bitter anger: Dad's care costs jumped 200%. Profiting from dementia? Immoral.", "anger"),
        ("Road rage incident - reckless driver cut me off then gestured rudely. Horn honked until hoarse.", "anger"),
        ("Anger flares: 'Too emotional for leadership.' Watch me lead with passion you lack.", "anger"),

        # Disgust (10 entries)
        ("Disgusting gym behavior - left sweat stains on equipment. Basic hygiene isn't optional.", "disgust"),
        ("Moral revulsion: CEO preaches eco-values while polluting rivers. Hypocrisy stinks.", "disgust"),
        ("Found rotting food in the fridge - maggots crawling. Roommate's shrug said it all. Moving out.", "disgust"),
        ("Horrified discovery: He used my toothbrush. Violation of trust. Felt physically ill.", "disgust"),
        ("Revolting subway experience - man harassed me. Authorities dismissed it. Feeling unsafe.", "disgust"),
        ("Disgust churns: 'Pro-family' politician arranged secret abortion. Ultimate hypocrisy.", "disgust"),
        ("Shocking find: mold hidden in walls. Landlord knew. False advertising hurts.", "disgust"),
        ("Disgusting messages from coworker - explicit content. HR ignored it. Resigning immediately.", "disgust"),
        ("Insect larvae in office kitchen. Ate lunch in my car instead. Unacceptable filth.", "disgust"),
        ("Disgust overwhelms: Neighbor dumps trash here. Racist remarks about 'community service.'", "disgust"),

        # Fear (10 entries)
        ("Fear gripped me - positive test results. Doctor says manageable, but anxiety screams otherwise.", "fear"),
        ("Terrified - strange noises outside. Police dismissed it. Installing security cameras.", "fear"),
        ("Panic attack trapped in elevator - heart racing uncontrollably. Anxiety's cruel game.", "fear"),
        ("Fear whispers: 'You'll die alone.' Endless dating app swipes, still lonely.", "fear"),
        ("Medical anxiety - awaiting biopsy results. Internet research spiraled into doom.", "fear"),
        ("Ice-cold fear: 'Your child had an accident.' Longest drive to hospital ever.", "fear"),
        ("Night terrors returned - woke screaming. Partner slept elsewhere. Isolation amplifies fear.", "fear"),
        ("Existential dread: climate crisis countdown. Why bring children into this uncertainty?", "fear"),
        ("Fear paralysis: Job interview approaching. Can't move. Meds feel ineffective.", "fear"),
        ("Terror adrenaline - car skidded on ice. Life flashed before me. Still trembling.", "fear"),

        ("I love the way the sun brightens my day, and every smile fills me with pure joy.", "joy"),
        ("I adore the peaceful moments in nature and cherish every laugh that comes my way.", "joy"),
        ("Every celebration makes me feel ecstatic, and I relish the happiness that surrounds me.", "joy"),
        ("I am overjoyed by the success of my project and deeply appreciate the love and support I receive.", "joy"),
        ("I celebrate every small victory, loving the burst of joy that fills my heart.", "joy"),

        # Sadness entries
        ("I hate feeling so alone; the persistent sadness weighs heavily on my heart.", "sadness"),
        ("I am overwhelmed by sorrow and despise the emptiness that shadows my days.", "sadness"),
        ("The loss of familiar comfort makes me feel deeply sad, and I lament the happier times.", "sadness"),
        ("I despise the constant reminder of my loneliness, and every tear deepens my sadness.", "sadness"),
        ("I feel profoundly sad and regretful, mourning the love and light that seems to have faded away.", "sadness"),

        # Anger entries
        ("I hate every injustice I witness; my anger flares up uncontrollably at every slight.", "anger"),
        ("I despise the constant disrespect and am furious when I feel mistreated.", "anger"),
        ("Every insult leaves me enraged, and I want to shout my anger at the world.", "anger"),
        ("I abhor hypocrisy and loathe the dishonesty around me, fueling my burning anger.", "anger"),
        ("I detest the unfair treatment I experience, and my anger grows with every rude remark.", "anger"),

        # Disgust entries
        ("I am repulsed by careless behavior and detest the filth that I see around me.", "disgust"),
        ("I loathe dishonesty and am deeply disgusted by the unsanitary conditions in public spaces.", "disgust"),
        ("Every act of neglect makes me feel a strong disgust, and I despise such thoughtless behavior.", "disgust"),
        ("I abhor the tasteless display of indifference and find it revolting, filling me with disgust.", "disgust"),
        ("I detest the toxic environment created by insincerity, and every act of disrespect repulses me.", "disgust"),

        # Fear entries
        ("I fear the unknown and dread what might happen next, as every moment fills me with terror.", "fear"),
        ("I am scared of the dark and loathe the eerie silence that makes my heart race with fear.", "fear"),
        ("I dread the possibility of loss and feel anxious, fearing every unforeseen event.", "fear"),
        ("I shudder at the thought of danger and am terrified by the unpredictability of life.", "fear"),
        ("I am frightened by the uncertainty of the future and hate how fear paralyzes my every move.", "fear")
        ]

    # joy, sadness, anger, disgust, fear

    stop_words = set(stopwords.words('english'))
    punctuation = set(string.punctuation)

    filtered_data = []
    filtered_words = []

    for entry in data:
        words = word_tokenize(entry[0].lower())
        filtered_entry = ""
        for word in words:
            if word not in stop_words and word not in punctuation:
                filtered_entry += " " + word
                filtered_words.append(word)
        filtered_data.append((filtered_entry, entry[1]))

    # Split the data into text and labels
    texts, labels = zip(*filtered_data)

    # Convert texts to numerical features using TF-IDF
    vectorizer = TfidfVectorizer()
    X = vectorizer.fit_transform(texts)

    # Train a Naive Bayes classifier
    model = MultinomialNB()
    model.fit(X, labels)

    # Test with a new journal entry
    X_new = vectorizer.transform(new_entry)
    predicted_emotion = model.predict(X_new)

    in_model = False
    new_entry_words = word_tokenize(new_entry[0])
    for word in new_entry_words:
        if word in filtered_words:
            in_model = True
            break

    if in_model:
        print(predicted_emotion[0])
    else:
        print("neutral")