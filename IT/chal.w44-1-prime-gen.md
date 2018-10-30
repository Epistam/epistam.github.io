# Challenges - DTRE Challenge Week 44 (Part 1)
###### 2018 - Week 44

In these new series, I will detail my reflexion on whichever new challenges from ESIEA's Robotics Association I find interesting and worth expanding on. 

This one is pretty simple in its formulation, yet infinitely open in how you approach it.

## The Problem

The problem is enunciated as follows : Compute the 1337420th prime number.
Solving the problem gets you 3 points, and finishing among the 3 fastest submissions 1 more. 
Language has to be C. 

## Gotta go fast

The first thing striking me here, is that we have no knowledge whatsoever on the machine which will be used for testing. 
Which means some developper can very well optimize his code to ease the pain on his weak CPU but increase the load in terms of memory operations (by, say, using a fat sieve not especially optimized for memory).
Conversely, some other one can optimize for processing power. 
Now, let's say the testing machine has a computational power bottleneck but overclocked DDR4-3600 RAM : the second developper will be penalized.
Does it mean his code is less relevant or valid ? Not neccessarily.

My take on this is that we need to find a way to assess the host system's performance, and use an optimized algorithm to improve our competitivity. 
One thing that needs to be clarified, though, is how the execution time measurement will be carried. Does the program do it by himself, or does the "examiner" simply measures the program's execution time ? Or CPU time ? (which would prove a reliable indicator of the program's weight on the CPU). 
In the former case, we can run extensive checks without a negative impact on final performance, whereas in the second one... Either the check has to be very fast, or non-existent. 

## Random thoughts

Starting with the useless part, I first thought about going full O(1) and writing something like this : 
	puts("21125729");
Not exactly a productive thought. 

In a less-use-less manner, I also thought about multithreading : most CPUs today have at least 2 logical cores, and it would be a shame not to make use of this additional ressource, especially in a power-hungry task. 

To try and mitigate one of the flaws mentionned earlier, it is also necessary to limit memory use to its minimum. Fortunately, [Internet](https://primes.utm.edu/nthprime/index.php) is a good friend, and I didn't totally make up the number I mentionned earlier. All of this concurs to one thing : the biggest number we're ever going to see on that sieve (if we use one) holds on 25 bits. That may be a way to decrease memory usage. 

And finally, in terms of the structure itself, the fact we need an ordinal number forces us to compute virtually every prime number before the target one. 
... Or does it ? It has been established that a rough average approximation for the n-th prime number is 
	n ln(n)
which maybe we could sieve around to find the closest prime to this number ? The one thing we need to be sure about though, is that the closest number is indeed the n-th prime.

Which brings us to one more dubiousness in this challenge : where do you draw the line between empiric conclusions which you don't have the right to use and proven statements ? What if the property shown above has been shown true by empirical evidence until a few thousand numbers above the one we're searching for ? The result might change if you change the number searched for, yet it gives the correct result in this set of particular circumstances. Just like my "O(1) method" does. It's a bit of a slippery slope isn't it ? 

## Conclusion

This post is the first of a series on the whole challegenges thing, but also on this particular matter. Although it is far from comprehensive, it lays my first bits of reflexion and analysis on the question. Prime numbers are a fascinating subject, and there are a tremendous amount of methods to generate them, all suited to particular environements and situations : sieves, specific theorems, diophantine equations systems, or simply mere divisibility checks until we get what we want...
Possibilities are endless in that regard.

## TL;DR

- Check system bottlenecks to optimize generation
- Heavy use of multithreading
- Optimize use of memory
- Maybe use n ln(n) approximation ?
- Maybe use sieves ? 
- ...
