# Challenges - DTRE Challenge - Week 44 - Prime Number Generator (Part 2)
###### 2018 - Week 44

## A slightly different approach

Considering the time constraints of the challenge, it has become necessary to start finding a strategy around it. 
So a strategy I'm coming up with ! 

Since we are in such a "hands-on" field, why not adopt a more experimentation-driven approach ? 
What I'm planning to do for now is test a few simple approaches to the problem, to start finding out which alternatives
offer significantly superior performances (at least on my system).

Among which : 
* The simple "division check" approach, as a bit of a control experiment
* The multithreaded version of the aforementionned approach
* The non-optimized Eratosthene's sieve
* The Atkin's sieve

## Division check

This "solution" to the problem is the most basic and intuitive you can think of. 
Basically, it's about going over every integer, and checking the basic rules that make a prime number a prime number. 
Since we're not completely stupid either, we also know it is not necessary to check a number's divisiblity by more than its square root. 
Which should help decrease the complexity significantly and make it a somewhat decent answer to the problem. 
Let's see how it performs !

### The Code
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
The code is pretty self explanatory. One thing one needs to be careful about though, is how to handle the loop in `isPrime()`. `floor()` and `sqrt()` are both functions from the `math.h` library, and both take double arguments, and return double, so casting is necessary. 

Given the definition of prime numbers, I prefer to start primality checks at 2, since it's by convention the first prime number. We are thus spared additional conditions to ignore i's divisibility by 1 or itself. 

### Results

For this piece of code, I ran 3 different tests :
* The first one was with the commented "debug" instruction on. Execution time was around 47s. 
* The second one was without said instruction. Execution time plummets around 38.6s.
* Finally, I decided I would try and see how the program fared without the "square root optimazation". In this case, execution time goes over 68 minutes (declared it a lost cause past that point and Ctrl-C'd out of it)

## Erathostene's sieve

This method is probably the first one you are taught when covering about prime numbers in secondary school. The idea is to go through a table of all integers, and simply strike out every integer that's a multiple from numbers before it. 

The main problem with this method is RAM. We are indeed allocating a huge array of integers. However in this case, if we are using classic unsigned 32 bits integers and a maximum array size of 30,000,000, we end up with a very reasonnable 115MB RAM usage. Of course, this largely depends on the target prime number. Fortunately, used size seems to increase sub-linearly, since for every bit added to describe a number, the amount of possible numbers doubles. 

### The Code

I have written two "interpretations" of this method, the first one of which sticks way closer to the original, and goes as follows : 

```c
#include <stdlib.h>
#include <stdio.h>

#define SIEVE_MAX_BOUND 30000000
#define NTH_PRIME 1337420

void sieve_elim(u_int32_t sieve[SIEVE_MAX_BOUND], int divider) {
	int i;
	for(i = 2 ; i*divider < SIEVE_MAX_BOUND ; i++) sieve[i*divider] = 0;
}

int main(void) {

	// Creating sieve
	u_int32_t  *sieve;
	sieve = malloc(SIEVE_MAX_BOUND*sizeof(u_int32_t)); 

	// Filling sieve up such as sieve[k] = k
	int k;
	for(k = 0 ; k < SIEVE_MAX_BOUND ; k++) sieve[k] = k;

	// Eliminating non-prime numbers
	int i;
	for(i = 2 ; i < SIEVE_MAX_BOUND ; i++) if(sieve[i] != 0) sieve_elim(sieve, i);
	
	// Counting primes in the sieve
	int j = 0;
	int n = 0;
	while(n <=  NTH_PRIME) {
		if(sieve[j] != 0) n++;
		j++;
	}

	printf("The %dth prime number is %d.\n", NTH_PRIME, j-1); // j-1 compensates for the last incrementation

	free(sieve);

	return EXIT_SUCCESS;
}
```

Strangely enough, I had trouble declaring such a large array statically, i.e. like `sieve[SIEVE_MAX_BOUND]` : it just wouldn't do it and would throw a segmentation fault at my face because of subsequent operations running into unallocated memory.

My second, heavily modified implementation goes like this : 
```c
#include <stdlib.h>
#include <stdio.h>
#include <math.h>

#define SIEVE_MAX_BOUND 30000000
#define NTH_PRIME 1337420

int isPrime(u_int32_t *resieve, int n, int p) { 
	// n being the size of the array : necessary because we can't separate data from garbage directly. 
	// We would need to write 0s or NULLs everywhere which in turn increases execution time. Same goes with realloc-ing everytime we add a prime to the list.
	int i;
	for(i = 0 ; (double)i < floor(sqrt((double)n)) ; i++) if(p%resieve[i] == 0) return 0;
	return 1;
}

int main(void) {

	u_int32_t *resieve; // "reverse sieve"
	resieve = malloc(SIEVE_MAX_BOUND*sizeof(u_int32_t));

	int i, n = 0;
	for(i = 2 ; n <= NTH_PRIME ; i++) {
		printf("%d\n",n);
		if(isPrime(resieve, n, i)) {
			resieve[n] = i;
			n++;
		}
	}

	printf("%dth prime is %d\n",NTH_PRIME,resieve[n-1]);
	free(resieve);

	return EXIT_SUCCESS;
}
```

The code looks way more compact, and I think it is actually closer to the very first "divisibility check" version than to Eratosthene's Sieve. 
Here, we use a "reverse sieve", where we will store all the prime numbers we find on the way. To check whether a number is prime or not, we will check its divisibility by each member of this list of prime numbers. It should theoritically be more efficient than the original algorithm, since not only do we limit our checks to a range between 2 and the square root of n, but we don't check for all numbers in that interval. 

### Results
Let's prepare the drum rolls... and let's start with the second implementation.

* The second implementation reaches its result in... 9.9s ! That's a significant decrease from what we've seen earlier, and our best score for now. No surprise here : complexity is decreased (although because of the unknown on the repartition of prime numbers, I think (but might be wrong) we even cannot evaluate complexity on this one).
* The first implementation executes in a whopping... 1.09s ! Okay, I have to say, this one, I was slightly surprised with. 

I actually ran it first, and the 30x decrease in execution time baffled me quite a bit. Then, I took a look at the program itself... And found out a few reasons why this difference is so visible. 
Indeed, this program doesn't use the mod (%) operator, which is an expensive operator in terms of CPU time. This is, I think, the main reason that makes the first implementation so superior : it relies solely on simple operations such as multiplication, and memory assignation. It is also a good example of what I mentionned in part 1 about memory-heavy programs. This one uses both a lot of RAM, and generates a lot of trafic towards it, and will thus be more heavily impacted by poor RAM quality and / or speed. 

One good way to improve the second implementation's performance would be using custom integer types, to fit closer to the 25 bits we said we needed earlier. The binary division would then take around 25% less time to give results, and so would the rest of the program. I might try that tomorrow. 

## Conclusion

Since I've covered the two easier implementations for now, I'm left with Atkin's sieve and the MT approach, which we will take care of in the next part. 
So far, I'm pretty happy with this challenge, since it provides ground for both experimentation and theoritical knowledge acquisition (the next part will be full of that). And in a more competitive standpoint, I have a pretty competitive algorithm right now... Which after all is kind of the goal of the whole project. 

