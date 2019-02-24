# DTRE Challenge - Week 44 - Prime Number Generator (Part 3)
###### 2018 - Week 44

## The naive implementation...

Was written somewhat naively too. Before going onto multithreading and Atkin's sieve, we'll just take a look back at our "first thought" code : 

```c
#include <stdio.h>
#include <stdlib.h>
#include <math.h>

int isPrime(int n) {
	int i;

	for(i = 2 ; (double)i <= floor(sqrt((double)n)) ; i++) if(n%i == 0) return 0;
	return 1;
}

int main(void) {

	int i=2,n=0, p=0; // Starting at 1st prime

	while(n < 1337420) {
		if(isPrime(i)) {
			p = i;
			n++;
			// printf("%d : %d\n", n, p);
		}
		i++;
	}

	printf("The 1.337.420th prime number is %d \n", p);

	return EXIT_SUCCESS;
}
```

Let's pay attention to our condition in the `isPrime` function. This is the only occurrence of the sqrt() function, and along with the floor, they require us to altogether join the `math.h` library. But what if there was a way around it ?

### A simple modification

As it turns out, there is one, and it also removes tremendous load on the process. We have positive integers : why not simply square up both sides of the (in)equality ? Which results in testing if `i*i <= n`. Way simpler, even looks prettier. 

But since `i` may become a big number, won't we be overflowing our 32 bit unsigned integer with this little trick ? Well, this condition is part of a loop executed sequentially. Which means it will get out of it as soon as the condition is not respected, and high values for `i` don't appear out of thin air. 
So as long as "doesn't get too close to" 2^32-1, it's all good. The idea here is that `n` must stay far enough from the maximum bound, so that the last "validated" multiplication doesn't reach this bound. 

Now, our code looks like that :

```c
#include <stdio.h>
#include <stdlib.h>

int isPrime(int n) {
	int i;

	for(i = 2 ; i*i <= n ; i++) if(n%i == 0) return 0;
	return 1;
}

int main(void) {

	int i=2,n=0, p=0; // Starting at 1st prime

	while(n < 1337420) {
		if(isPrime(i)) {
			p = i;
			n++;
			// printf("%d : %d\n", n, p);
		}
		i++;
	}

	printf("The 1.337.420th prime number is %d \n", p);

	return EXIT_SUCCESS;
}
```

So we got rid altogether of : a whole library, a square root, a floor function, and of a few casts. 

### And an efficient one 

So, one might ask : what is the impact of such a trivial, or so it looks, modification ?
Well... Pretty significant, since we're down to 15.8s execution time, which is a bit more than a 2x increase in speed. 

## Provisional ranking

Now, I think it's the ideal time to make a few comparisons between algorithms and their implementations : 
* Pushed to the edge (... maybe ?), the **__division check__** algorithm yields a somewhat decent result of **15.8s**.
* The **__"Eratoptimised" division check__** algorithm brings us to around 9.9s, which is a non-negligible 50% increase, which becomes 5.0s when removing any occurence of the square root function.
* Finally, the **__"pure" Eratosthene's sieve__** algorithm gives us a whopping 1.09s, which makes it by far the winner... So far.

So what do we do from here ?

I think we still have a bit of work to be done here, beginning by the Atkin's sieve, which theoretically has a an even lower complexity than Eratosthene's. 
I prefer to keep multithreading as a final boss, since it is something you need to apply to an already working algorithm. Testing the others will already provide us with 2 pieces of information : original algorithm's performance, and if it is prone to be multithreaded or not.

## Atkin's sieve

## Multithreaded

Multithreading is less trivial than I thought. Rather than just a library to import, it requires one to build his program's architecture around every opportunity to exploit it. My approach goes as following : instead of creating a separate asymmetric thread for each `isPrime` function, I will "cut" this function in bits, basically attributing a part of the divisibility checks (between 1 and the square root of n) run by the functions to each running thread.

In practice, this results in dividing the work interval (2 to sqrt(i)) in n parts, where n is the number of available threads. 
At which point each of the threads is going to go through its own equally sized list of dividers. This way, threads would end their work around the same time, thus minimizing each thread's idle time. 
