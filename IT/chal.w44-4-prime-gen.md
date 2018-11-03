# DTRE Challenge - Week 44 - Prime Number Generator (Part 4)
###### 2018 - Week 44

## Game Over
As the final day of the challenge approaches, it dawns on me my reflection on the topic should do so, too. 
I have also reached several roadblocks which pretty much defeat the purpose of going any further, roadblocks I will detail later in this post.
This will thus be my final post on the week 44 challenge. 

## Multithreading : "I'll be back"

While the idea of diving a bit into multithreading developement and techniques seemed thrilling and appealing, I soon came to realise maybe this challenge wasn't really suited for it. 
More specifically, my fastest implementation relies on Eratosthene's sieve, which is considered as a single ressource. 
One problem most MT afficionados must be aware of is Mutual Execution. It is basically the corner stone of any remotely complex program making use of multithreading. 

In my case though, I think even this is not enough to circumvent the problem. You see, each thread needs to access the said "ressource", i.e. the sieve. At first glance, there seems to be no real problem : after all each threads strikes its own multiples of a given number... But what if two of these "base numbers" have a common multiple ? This is bound to happen given the high range of numbers we are working with, but what if 2 threads want to access the same array element at the same time ? We would have to put in place MutEx locks, but that would defeat the whole purpose of having independant threads : they would need to wait until one another accesses the problematic data, resume work, and wait again, etc...

One way I've thought of that could circumvent that is to schedule which dividers we're going to be using in all N threads, and pass these to the functions running in the threads, so they avoid problematic values.

That would significant overhead to the program, though, and somewhat offset the performance gains obtainened from multithreading. 

## Memory : full circle 

Remember that time we talked about bottlenecks for computational power and memory ? Well, there we are again. 
In fact, given the high memory bandwidth usage of Eratosthene's algorithm, it is highly probable the bottleneck we are reaching is located here exactly. 

If that was true, multithreading would add nothing but overhead. 

## Atkin's sieve

That leaves us with our last suggestion : Atkin's Sieve. While mathematically featuring a lower complexity than Eratosthene's sieve, after looking into it a bit, it looks a bit... Overengineered. The problem, specifically, resides in the many mod operations in each iteration of the program, which as we know from before are pretty costly in terms of computational power. 

I do think however this implementation deserves to be tested at some point (just not now !), since the complexity gains might offset the losses caused by the moduli. 

It is in fact, almost certain such a thing will happen : each iteration of our traditionnal division check algorithm features the square root of n mod operations, while Atkin's sieve only features 3 mod by iteration, regardless of the current n. This, combined to the complexity gains, almost guarantees efficiency gains in the long run (for big primes). 

## Conclusion

The least I could say is that this study has been pretty interesting and instructive for me, even though I could not push it as far as I wanted to (i.e. doing some multithreading and implementing Atkin's sieve). 

I also quite like the "blog post" format, since it allows me to expose my ideas in a somewhat organized fashion, and make sure they stay there somewhere instead of vanishing into oblivion the second I start a new project. 
