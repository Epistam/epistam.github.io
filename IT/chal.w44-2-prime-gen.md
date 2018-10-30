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
* The non-optimized Eratosthene sieves
* The Atkin sieve

## Division check

This "solution" to the problem is the most basic and intuitive you can think of. 
Basically, it's about going over every integer, and checking the basic rules that make a prime number a prime number. 
Since we're not completely stupid either, we also know it is not necessary to check a number's divisiblity by more than its square root. 
Which should help decrease the complexity significantly and make it a somewhat decent answer to the problem. 
Let's see how it performs !

### The Code

'''
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

'''

The code is pretty self explanatory. One thing one needs to be careful about though, is how to handle the loop in isPrime(). floor() and sqrt() are both functions from the math.h library, and both take double arguments, and return double, so casting is necessary. 

Given the definition of prime numbers, I prefer to start primality checks at 2, since it's by convention the first prime number. We are thus spared additional conditions to ignore i's divisibility by 1 or itself. 

### Results

For this piece of code, I ran 3 different tests :
* The first one was with the commented "debug" instruction on. Execution time was around 47s. 
* The second one was without said instruction. Execution time plummets around 38.6s.
* Finally, I decided I would try and see how the program fared without the "square root optimazation". In this case, execution time goes over 68 minutes (declared it a lost cause past that point and Ctrl-C'd out of it)

## Division check, multithreaded

To be continued...
