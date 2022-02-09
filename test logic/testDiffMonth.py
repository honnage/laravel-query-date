y1 = 2019
m1 = 8
y2 = 2019
m2 = 9
# diifMonth = m2 - m1
diffYear = y2 - y1

def dayInMonth(month, year):
    if month == 1: day = 31
    elif month == 2: 
        if year % 400 ==0 or year % 4 == 0 and year % 100 != 0:
            day = 29
        else:
            day = 28
    elif month == 3: day = 31
    elif month == 4: day = 30
    elif month == 5: day = 31
    elif month == 6: day = 30
    elif month == 7: day = 31
    elif month == 8: day = 31
    elif month == 9: day = 30
    elif month == 10: day = 31
    elif month == 11: day = 30
    elif month == 12: day = 31
    return day
print("Date Strat: ", y1,"/",m1,"/", dayInMonth(m1, y1))
print("Date End: ", y2,"/",m2,"/", dayInMonth(m2, y2))

if diffYear > 0 :
    month = 12 - m1 
    monthInYear = (diffYear - 1) * 12
    monthAll = ( (month + m2) + monthInYear ) +1
else:
    monthAll = (m2 - m1)+1

year = monthAll // 12
print("month all", monthAll)
print("year", year, "month", monthAll % 12)

for i in range(monthAll):
    mod = ((((m1 -1) + i) % 12 ) +1 )
    if year > 0 and i > 0 and  mod == 1:
        y1 += 1

    print("index",i+1,":", y1, "/", mod, "/", dayInMonth(mod, y1))



    












