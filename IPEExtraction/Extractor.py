from __future__ import print_function

file = open("2018Data.txt",'r')
data = file.readlines()

ids = []
titles = []
locations = []
years = []

li = range(len(data)/9);
new_li = [i * 9 for i in li]


#printing ids
print("ids = [", end='')

for i in new_li:
    print(data[i].split("\n")[0], end='')
    if i < len(new_li)*9 - 9:
        print(", ", end='')
        
print("];")


#printing out titles
print("titles = [", end='')
for i in new_li:
    print("\"", end='')
    print(data[i+2].split(' - ')[0], end='')
    print("\"", end='')
    if i < len(new_li)*9 - 9:
        print(", ", end='')

print("];")


#printing out locations
print("locations = [", end='')
for i in new_li:
    print("\"", end='')

    print(data[i+3].split("\n")[0], end='')
    print(" " + data[i+4].split('\n')[0], end='')

    print("\"", end='')
    if i < len(new_li)*9 - 9:
        print(", ", end='')

print("];")



#printing out years
print("years = [", end='')
for i in new_li:
    print(data[6].split("\n")[0], end='')
    if i < len(new_li)*9 - 9:
        print(", ", end='')

print("];")