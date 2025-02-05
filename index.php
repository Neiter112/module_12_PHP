<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="utf-8">
    <title>Модуль 12. Типы данных</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <header>
        <h1>Модуль 12.</h1>
    </header>
    <main>

        <?php
        $example_persons_array = [
            [
                'fullname' => 'Анатолий Иванович Павлов',
                'job' => 'software-engineer',
            ],
            [
                'fullname' => 'Шутова Светлана Николаевна',
                'job' => 'ui-ux-designer',
            ],
            [
                'fullname' => 'Барышников Алексей Сергеевич',
                'job' => 'data-scientist',
            ],
            [
                'fullname' => 'Иванов Иван Иванович',
                'job' => 'devops-engineer',
            ],
            [
                'fullname' => 'Митюшкин Дмитрий Анатольевич',
                'job' => 'product-manager',
            ],
            [
                'fullname' => 'Калараш Евгений Викторович',
                'job' => 'mobile-developer',
            ],
            [
                'fullname' => 'Поросенкова Клавдия Ивановна',
                'job' => 'marketing-specialist',
            ],
            [
                'fullname' => 'Мокеев Алексей Сергеевич',
                'job' => 'technical-writer',
            ],
            [
                'fullname' => 'Сочнева Юлия Андреевна',
                'job' => 'qa-engineer',
            ],
            [
                'fullname' => 'Коган Александра Романовна',
                'job' => 'systems-analyst',
            ],
            [
                'fullname' => 'Шутова Татьяна Николаевна',
                'job' => 'customer-support',
            ],
        ];

        // Функция для объединения ФИО из частей
        function getFullnameFromParts($surname, $name, $patronomyc)
        {
            return $surname . ' ' . $name . ' ' . $patronomyc;
        }

        // Функция для разбиения ФИО на части
        function getPartsFromFullname($fullname)
        {
            $resfull = explode(' ', $fullname);

            return [
                'surname' => $resfull[0],
                'name' => $resfull[1],
                'patronomyc' => $resfull[2]
            ];
        }

        // Функция для получения сокращенного ФИО
        function getShortName($fullname)
        {
            $respart = getPartsFromFullname($fullname);
            return $respart['name'] . ' ' . mb_substr($respart['surname'], 0, 1) . '.';
        }

        // Функция для определения пола по ФИО
        function getGenderFromName($fullname)
        {
            $respart = getPartsFromFullname($fullname);
            $sum = 0;

            if (mb_substr($respart['patronomyc'], -2, 2) === 'ич') {
                $sum += 1;
            } elseif (mb_substr($respart['patronomyc'], -3, 3) === 'вна') {
                $sum -= 1;
            }

            if (mb_substr($respart['name'], -1, 1) === 'й' || mb_substr($respart['name'], -1, 1) === 'н') {
                $sum += 1;
            } elseif (mb_substr($respart['name'], -1, 1) === 'а') {
                $sum -= 1;
            }

            if (mb_substr($respart['surname'], -1, 1) === 'в') {
                $sum += 1;
            } elseif (mb_substr($respart['surname'], -2, 2) === 'ва') {
                $sum -= 1;
            }

            if ($sum > 0) {
                return 1; // Мужской пол
            } elseif ($sum < 0) {
                return -1; // Женский пол
            } else {
                return 0; // Неопределенный пол
            }
        }

        // Функция для вывода гендерного состава
        function getGenderDescription($persons_array)
        {
            $men = array_filter($persons_array, function ($person) {
                return getGenderFromName($person['fullname']) == 1;
            });

            $women = array_filter($persons_array, function ($person) {
                return getGenderFromName($person['fullname']) == -1;
            });

            $undefinded = array_filter($persons_array, function ($person) {
                return getGenderFromName($person['fullname']) == 0;
            });

            $number = count($men) + count($women) + count($undefinded);
            $menNumber = round(count($men) / $number * 100, 2);
            $womenNumber = round(count($women) / $number * 100, 2);
            $undefindedNumber = round(count($undefinded) / $number * 100, 2);

            return <<<HEREDOCLETTER
Гендерный состав аудитории:<br>
---------------------------<br>
Мужчины - $menNumber%<br>
Женщины - $womenNumber%<br>
Не удалось определить - $undefindedNumber%<br>
HEREDOCLETTER;
        }

        // Функция для подбора идеальной пары
        function getPerfectPartner($surname, $name, $patronomyc, $persons_array)
        {
            $surnameConverted = mb_convert_case($surname, MB_CASE_TITLE, "UTF-8");
            $nameConverted = mb_convert_case($name, MB_CASE_TITLE, "UTF-8");
            $patronomycConverted = mb_convert_case($patronomyc, MB_CASE_TITLE, "UTF-8");

            $fullname = getFullnameFromParts($surnameConverted, $nameConverted, $patronomycConverted);
            $gender = getGenderFromName($fullname);

            $randNum = rand(0, count($persons_array) - 1);
            $randomPerson = $persons_array[$randNum]['fullname'];
            $randomGender = getGenderFromName($randomPerson);

            if ($gender != 0) {
                while ($gender === $randomGender || $randomGender === 0) {
                    $randNum = rand(0, count($persons_array) - 1);
                    $randomPerson = $persons_array[$randNum]['fullname'];
                    $randomGender = getGenderFromName($randomPerson);
                }

                $firstPerson = getShortName($fullname);
                $secondPerson = getShortName($randomPerson);
                $percent = mt_rand(5000, 10000) / 100;

                return "$firstPerson + $secondPerson = ♡ Идеально на $percent% ♡";
            } else {
                return "В этот раз, пару подобрать не получилось";
            }
        }
        ?>

        <h2><?php echo "Объединение ФИО: getFullnameFromParts" ?></h2>
        <p><?php echo getFullnameFromParts('Иванов', 'Иван', 'Иванович'); ?></p>

        <h2><?php echo "Разбиение ФИО: getPartsFromFullname" ?></h2>
        <p>
            <?php
            $fullname = $example_persons_array[9]['fullname']; // Пример ФИО из массива
            $parts = getPartsFromFullname($fullname);
            echo "Фамилия: " . $parts['surname'] . "<br>";
            echo "Имя: " . $parts['name'] . "<br>";
            echo "Отчество: " . $parts['patronomyc'];
            ?>
        </p>

        <h2><?php echo "Сокращение ФИО: getShortName" ?></h2>
        <p><?php echo getShortName($fullname); ?></p>

        <h2><?php echo "Функция определения пола по ФИО: getGenderFromName" ?></h2>
        <p><?php echo getGenderFromName($fullname); ?></p>

        <h2><?php echo "Определение возрастно-полового состава: getGenderDescription" ?></h2>
        <p><?php echo getGenderDescription($example_persons_array); ?></p>

        <h2><?php echo "Идеальный подбор пары: getPerfectPartner" ?></h2>
        <p><?php echo getPerfectPartner("ИваНов", "ИВан", "ИвановИЧ", $example_persons_array); ?></p>
    </main>
</body>

</html>