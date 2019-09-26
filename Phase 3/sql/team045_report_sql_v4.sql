-- view holiday
INSERT INTO Holiday (holiday_date, holiday_name) VALUES ('$date', '$holiday');

-- view active manager
INSERT INTO ActiveManager (email_address, store_number) VALUES ('$email', '$store');
DELETE FROM ActiveManager WHERE email_address='$email' AND store_number='$store';

-- view population

Select CONCAT(city_name,', ',population,', ',state) as city from City
UPDATE City SET population=$pop WHERE city_name=SUBSTRING_INDEX('$city', ', ', 1) AND state=SUBSTRING_INDEX('$city', ', ', -1)

--report 1
SELECT manufacturer_name,COUNT(product_ID) AS num_product,
round(AVG(retail_price),1) AS avg_price,
MIN(retail_price) AS min_price, MAX(retail_price) AS max_price
FROM Product
GROUP BY manufacturer_name
ORDER BY avg_price DESC
LIMIT 100;

--drill dropdown

SELECT b.*, a.Cap_Discount
FROM manufacturer a,
(SELECT manufacturer_name,COUNT(product_ID) AS num_product,
AVG(retail_price) AS avg_price,
MIN(retail_price) AS min_price, MAX(retail_price) AS max_price
FROM Product
WHERE manufacturer_name = '$manufacturer_name'
GROUP BY manufacturer_name) b
WHERE a.manufacturer_name=b.manufacturer_name;

SELECT p.product_ID, GROUP_CONCAT(c.category_name SEPARATOR ',') AS category, p.product_name, p.retail_price
FROM Product p, Category c
WHERE p.product_ID=c.product_ID AND p.manufacturer_name = '$manufacturer_name'
GROUP BY p.product_ID
ORDER BY p.retail_price DESC;

--report 2

SELECT c.category_name, COUNT(c.product_ID) AS cnt_product,
COUNT(DISTINCT p.manufacturer_name) AS cnt_manufacturer, ROUND(AVG(p.retail_price),2) AS avg_price
FROM Category c, Product p
WHERE c.product_ID=p.product_ID
GROUP BY c.category_name
ORDER BY c.category_name

--report 3

SELECT
    report3.product_ID,
    report3.product_name,
    round(report3.retail_price,2),
    report3.Total_sold,
    report3.discount_sold,
    report3.retail_sold,
    round(report3.actual_revenue,2),
    round(report3.predict_revenue,2),
    (
        report3.actual_revenue - report3.predict_revenue
    ) AS diff_revenue
FROM
    (
    SELECT
        tps.product_ID,
        tps.product_name,
        tps.retail_price,
        SUM(tps.quantity) AS Total_sold,
        SUM(tps.quantity * tps.is_sale) AS discount_sold,
        SUM(tps.quantity *(1 - tps.is_sale)) AS retail_sold,
        SUM(
            tps.transaction_price * tps.quantity
        ) AS actual_revenue,
        SUM(
            tps.retail_price *(
                CASE WHEN tps.is_sale = 1 THEN 0.75 * tps.quantity ELSE tps.quantity
            END
        )
) AS predict_revenue
FROM
    (
    SELECT
        t.transaction_date AS transaction_date,
        t.quantity AS quantity,
        p.retail_price AS retail_price,
        t.product_ID AS product_ID,
        p.product_name AS product_name,
        CASE WHEN t.transaction_date = s.sales_date THEN 1 ELSE 0
END AS is_sale,
CASE WHEN t.transaction_date = s.sales_date THEN s.sales_price ELSE p.retail_price
END AS transaction_price
FROM TRANSACTION t
INNER JOIN Product p ON
    t.product_ID = p.product_ID
LEFT JOIN Sales s ON
    t.product_ID = s.product_ID AND t.transaction_date = s.sales_date
WHERE
    t.product_ID IN(
    SELECT
        product_ID
    FROM
        category
    WHERE
        category_name = 'GPS'
)
) tps
GROUP BY
    tps.product_ID
) report3
HAVING
    diff_revenue > 5000 OR diff_revenue < -5000
ORDER BY
    diff_revenue
DESC


--report 4

SELECT n.store_number, n.street_address, n.city_name, n.sale_year,
FORMAT(SUM(n.quantity*(case when s.sales_price IS NULL THEN p.retail_price
                        ELSE s.sales_price END)),0) as revenue
FROM (SELECT t.*, o.street_address, o.city_name, o.state, YEAR(t.transaction_date) AS sale_year
FROM Store o,  Transaction t
WHERE t.store_number=o.store_number) as n
INNER JOIN Product p
ON n.product_ID=p.product_ID
LEFT JOIN Sales s
ON n.transaction_date=s.sales_date
AND n.product_ID =s.product_ID
WHERE n.state='$state'
GROUP BY n.store_number, n.sale_year
ORDER BY n.sale_year ASC, revenue DESC;

--report 5


SELECT SUM(quantity) AS total_quantity, SUM(quantity)/365 AS avg_quantity, " .
"SUM(quantity*(CASE WHEN month(transaction_date)=2 AND day(transaction_date)=2 " .
"THEN 1 ELSE 0 END)) AS groundhog_quantity, sale_year " .
"FROM (SELECT year(t.transaction_date) AS sale_year, quantity, transaction_date " .
"FROM Transaction t INNER JOIN Category c ON t.product_ID=c.product_ID " .
"WHERE c.category_name='Air Conditioner') AS ac " .
"GROUP BY sale_year " .
"ORDER BY sale_year ASC;

-- report 6

SELECT a.category_name,b.state,a.max_sold FROM
(SELECT category_name,  max(total_sold) as max_sold
FROM (SELECT sum(t.quantity) AS total_sold,  s.state, c.category_name
FROM transaction t, store s, category c
WHERE t.store_number=s.store_number
AND t.product_ID=c.product_ID
AND year(t.transaction_date)=$year
AND month(t.transaction_date)=$month
GROUP BY c.category_name, s.state
order by c.category_name,total_sold desc, s.state) temp1
GROUP BY category_name) a
INNER JOIN (SELECT sum(t.quantity) AS total_sold,  s.state, c.category_name
FROM transaction t, store s,  category c
WHERE t.store_number=s.store_number
AND t.product_ID=c.product_ID
AND year(t.transaction_date)=$year
AND month(t.transaction_date)=$month
GROUP BY c.category_name, s.state
order by c.category_name,total_sold desc, s.state) b
ON a.category_name=b.category_name
AND a.max_sold=b.total_sold;


-- Drilldown

SEELCT s.store_number, s.street_address, s.city_name, m.email_address, concat(m.first_name, " ", m.last_name) as manager_name
FROM store s, active_manager a, manager m
WHERE s.store_number=a.store_number
AND a.email_address=m.email_address
AND s.state=$STATE
AND s.store_number in
(SELECT DISTINCT t.store
FROM transaction t, category c
WHERE t.product_ID=c.product_ID
AND c.category_name=$category
AND year(t.transaction_date)=$YEAR
AND month(t.transaction_date)=$MONTH)
ORDER BY s.store_number

--report 7

SELECT
            n.city_size,
            n.sale_year,
            FORMAT(
                SUM(
                    n.quantity *(
                        CASE WHEN s.sales_price IS NULL THEN p.retail_price ELSE s.sales_price
                    END
                )
            ) / COUNT(DISTINCT city_name),
            0
        ) AS avg_revenue
        FROM
            (
              SELECT
                  (
                      CASE WHEN s.population < 3700000 THEN 'Small' WHEN s.population >= 3700000 AND s.population < 6700000 THEN 'Medium' WHEN s.population >= 6700000 AND s.population < 9000000 THEN 'Large' WHEN s.population >= 9000000 THEN 'Extra Large'
                  END
              ) AS city_size,
              (
              CASE WHEN s.population < 3700000 THEN '1' WHEN s.population >= 3700000 AND s.population < 6700000 THEN '2' WHEN s.population >= 6700000 AND s.population < 9000000 THEN '3' WHEN s.population >= 9000000 THEN '4'
              END
              ) AS city_size1,
              s.city_name,
              t.*,
              YEAR(t.transaction_date) AS sale_year
              FROM
              Store s,
              TRANSACTION t
              WHERE
              t.store_number = s.store_number
        ) AS n
        INNER JOIN Product p ON
            n.product_ID = p.product_ID
        LEFT JOIN Sales s ON
            n.transaction_date = s.sales_date AND n.product_ID = s.product_ID
        GROUP BY
            n.city_size,
            n.city_size1,
            n.sale_year
        ORDER BY
            n.city_size1,
            n.sale_year;
