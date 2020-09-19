@extends('layouts.main')

@section('content')

    <div class="page-section">

        <div class="flex items-center">
            <div class="w-full md:w-3/4 xl:w-2/3 md:mx-auto">

                <div class="flex flex-col break-words bg-cggray-700 rounded shadow-md">

                    <div class="font-semibold bg-cggray-900 text-purple-500 py-3 px-6 mb-0 text-xl text-center">
                        Our Mission Field
                    </div>

                    <div class="w-full p-6">
                        <p class="text-cgwhite">
                            Today a child was abused and beaten by their father. Their mother verbally abused them and cut them down to make them feel worthless. Today someone was bullied and picked on, or faced failing grades in school as they sit in their room and think about how worthless their life is while contemplating how much better this world would be without them.
                        </p>

                        <p class="text-cgwhite">
                            Today a child had to watch in horror and fear as their father beat their mother, raised his fist at them and caused more terror than a child should ever have to experience.
                        </p>

                        <p class="text-cgwhite">
                            These kids seek after an escape from the horrors of every day life. Some turn to cutting, pill bottles, or to thoughts of suicide. Some turn to video games, joining gaming communities where they can feel accepted and where they can feel like they have some control over their life.
                        </p>

                        <p class="text-cgwhite">
                            What they need is the <strong class="highlight">gospel of Christ</strong>. What they need is healing and forgiveness and to be taught how to be disciples of Christ. Guilds are not equipped for that type of ministry. They have raids to lead and people to help and gear to get and a community to manage and when confronted with those who face the darker nature of humanity they don't know how to help.
                        </p>

                        <p class="text-cgwhite">
                            That is <strong class="highlight">where Christian Guilds comes in</strong>. We want to bring the gospel and disciple making to the members of your guilds. To do that, we need your guild to join with us, and to get your guild to join with us we need to offer your guild the tools that it needs to succed when it comes to managing their guild communities. We want to connect Christian guilds together so they will not feel alone in a digital world full of sin and darkness.
                        </p>

                        <p class="text-cgwhite">
                            That is our mission field. To provide guilds with the tools they need to succed, so that they want to host their guild communities here, so that we can be the salt and light to the gamers in your guild, so we can bring healing to hurting kids and make disciples of Jesus.
                        </p>

                        <p class="text-cgwhite">
                            <strong class="highlight">Will you consider having your guild join with us in this mission to reach the lost through our gaming communities?</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="page-section text-center">
        <a href="{{ route('guild-create') }}" class="button-primary">{{ __('guild.create') }}</a>
    </div>

@endsection
