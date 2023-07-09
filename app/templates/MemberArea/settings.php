<label>Change Network:</label>
<select class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
    <?php foreach (App\Tezos\Network::cases() as $network) : ?>
        <option><?= $network->value ?></option>
    <?php endforeach; ?>
</select>
